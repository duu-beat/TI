<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetResponsibilityTerm;
use App\Models\User;
use App\Services\AssetResponsibilityTermPdfService;
use App\Services\AssetResponsibilityTermService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetResponsibilityTermController extends Controller
{
    public function create(Asset $asset): View
    {
        $asset->load('user');
        $recipients = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.assets.terms.create', compact('asset', 'recipients'));
    }

    public function store(Request $request, Asset $asset, AssetResponsibilityTermService $terms): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:delivery,return'],
            'recipient_id' => ['nullable', 'exists:users,id'],
        ]);

        $recipientId = $validated['type'] === AssetResponsibilityTerm::TYPE_RETURN
            ? $asset->user_id
            : $validated['recipient_id'];

        if (! $recipientId) {
            return back()->withInput()->with('error', 'Selecione o responsável que receberá o ativo antes de emitir o termo.');
        }

        $recipient = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->findOrFail($recipientId);

        $term = $terms->issue($asset, $recipient, $request->user(), $validated['type']);

        return redirect()->route('admin.assets.terms.sign', [$asset, $term])
            ->with('success', 'Termo emitido. Solicite a assinatura do responsável no dispositivo.');
    }

    public function sign(Asset $asset, AssetResponsibilityTerm $term): View
    {
        $this->ensureTermBelongsToAsset($asset, $term);
        $term->load('asset', 'recipient', 'issuer');

        abort_unless($term->isPending(), 409, 'Este termo não está disponível para assinatura.');

        return view('admin.assets.terms.sign', compact('asset', 'term'));
    }

    public function storeSignature(Request $request, Asset $asset, AssetResponsibilityTerm $term, AssetResponsibilityTermService $terms, AssetResponsibilityTermPdfService $pdfs): RedirectResponse
    {
        $this->ensureTermBelongsToAsset($asset, $term);

        $request->validate([
            'signature' => ['required', 'string', 'max:2000000'],
        ], [
            'signature.required' => 'A assinatura é obrigatória para concluir o termo.',
        ]);

        try {
            $signature = $this->decodeSignature($request->string('signature')->toString());
            $signedTerm = $terms->sign($term, $signature, (string) $request->ip(), $request->userAgent());
            $pdfs->generate($signedTerm);
        } catch (\InvalidArgumentException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        } catch (\LogicException $exception) {
            return redirect()->route('admin.assets.show', $asset)->with('warning', $exception->getMessage());
        }

        return redirect()->route('admin.assets.show', $asset)
            ->with('success', 'Termo assinado e movimentação do ativo registrada com sucesso.');
    }

    public function download(Asset $asset, AssetResponsibilityTerm $term, AssetResponsibilityTermPdfService $pdfs)
    {
        $this->ensureTermBelongsToAsset($asset, $term);
        abort_unless($term->isSigned(), 404);

        if (! $term->pdf_path || ! Storage::disk('local')->exists($term->pdf_path)) {
            $term = $pdfs->generate($term);
        }

        return Storage::disk('local')->download($term->pdf_path, $pdfs->downloadName($term), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function cancel(Asset $asset, AssetResponsibilityTerm $term): RedirectResponse
    {
        $this->ensureTermBelongsToAsset($asset, $term);

        if ($term->isPending()) {
            $term->update(['status' => AssetResponsibilityTerm::STATUS_CANCELLED]);
        }

        return redirect()->route('admin.assets.show', $asset)->with('info', 'Termo pendente cancelado.');
    }

    private function ensureTermBelongsToAsset(Asset $asset, AssetResponsibilityTerm $term): void
    {
        abort_unless($term->asset_id === $asset->id, 404);
    }

    /**
     * Aceita exclusivamente PNG originado do canvas, evitando persistir
     * conteúdo arbitrário no armazenamento privado do sistema.
     */
    private function decodeSignature(string $dataUri): string
    {
        if (! preg_match('/^data:image\\/png;base64,([A-Za-z0-9+\\/=]+)$/', $dataUri, $matches)) {
            throw new \InvalidArgumentException('A assinatura enviada é inválida. Assine novamente no quadro indicado.');
        }

        $binary = base64_decode($matches[1], true);
        $image = $binary ? @getimagesizefromstring($binary) : false;

        if (! $binary || ! $image || ($image['mime'] ?? null) !== 'image/png' || strlen($binary) > 1_500_000) {
            throw new \InvalidArgumentException('Não foi possível validar a assinatura. Limpe o quadro e assine novamente.');
        }

        return $binary;
    }
}
