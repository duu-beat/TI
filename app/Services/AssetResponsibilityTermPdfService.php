<?php

namespace App\Services;

use App\Models\AssetResponsibilityTerm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetResponsibilityTermPdfService
{
    public function generate(AssetResponsibilityTerm $term): AssetResponsibilityTerm
    {
        if (! $term->isSigned()) {
            throw new \LogicException('O PDF só pode ser gerado após a assinatura do termo.');
        }

        $term->loadMissing('asset', 'recipient', 'issuer');

        $signatureDataUri = $term->signature_path && Storage::disk('local')->exists($term->signature_path)
            ? 'data:image/png;base64,' . base64_encode(Storage::disk('local')->get($term->signature_path))
            : null;

        $pdf = Pdf::loadView('admin.assets.terms.pdf', [
            'term' => $term,
            'signatureDataUri' => $signatureDataUri,
        ])->setPaper('a4');

        $pdfPath = sprintf(
            'asset-responsibility-terms/%d/pdfs/termo-%d-%s.pdf',
            $term->asset_id,
            $term->id,
            Str::slug($term->typeLabel()),
        );

        Storage::disk('local')->put($pdfPath, $pdf->output());
        $term->update(['pdf_path' => $pdfPath]);

        return $term->fresh(['asset', 'recipient', 'issuer']);
    }

    public function downloadName(AssetResponsibilityTerm $term): string
    {
        return sprintf(
            'termo-%s-%s-%s.pdf',
            $term->type === AssetResponsibilityTerm::TYPE_RETURN ? 'devolucao' : 'entrega',
            Str::slug($term->asset->tag),
            $term->signed_at?->format('Ymd-Hi') ?? $term->id,
        );
    }
}
