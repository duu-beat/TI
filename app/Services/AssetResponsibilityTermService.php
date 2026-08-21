<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\AssetResponsibilityTerm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetResponsibilityTermService
{
    public function issue(Asset $asset, User $recipient, User $issuer, string $type): AssetResponsibilityTerm
    {
        return DB::transaction(function () use ($asset, $recipient, $issuer, $type) {
            AssetResponsibilityTerm::query()
                ->where('asset_id', $asset->id)
                ->where('type', $type)
                ->where('status', AssetResponsibilityTerm::STATUS_PENDING)
                ->update(['status' => AssetResponsibilityTerm::STATUS_CANCELLED]);

            return AssetResponsibilityTerm::create([
                'asset_id' => $asset->id,
                'recipient_id' => $recipient->id,
                'issued_by' => $issuer->id,
                'type' => $type,
                'status' => AssetResponsibilityTerm::STATUS_PENDING,
                'terms_text' => $this->termsText($asset, $recipient, $type),
            ]);
        });
    }

    /**
     * Persiste a assinatura em armazenamento privado e aplica a movimentação
     * somente depois de registrar a evidência necessária para auditoria.
     */
    public function sign(AssetResponsibilityTerm $term, string $signatureBinary, string $ipAddress, ?string $userAgent): AssetResponsibilityTerm
    {
        if (! $term->isPending()) {
            throw new \LogicException('Este termo não está disponível para assinatura.');
        }

        $signaturePath = sprintf(
            'asset-responsibility-terms/%d/signatures/%s.png',
            $term->asset_id,
            Str::uuid(),
        );

        Storage::disk('local')->put($signaturePath, $signatureBinary);

        try {
            return DB::transaction(function () use ($term, $signaturePath, $signatureBinary, $ipAddress, $userAgent) {
                $term->loadMissing('asset', 'recipient', 'issuer');
                $asset = $term->asset;
                $previousRecipientId = $asset->user_id;
                $newRecipientId = $term->type === AssetResponsibilityTerm::TYPE_DELIVERY
                    ? $term->recipient_id
                    : null;

                $term->update([
                    'status' => AssetResponsibilityTerm::STATUS_SIGNED,
                    'signature_path' => $signaturePath,
                    'signature_hash' => hash('sha256', $signatureBinary),
                    'signed_at' => now(),
                    'signed_ip' => $ipAddress,
                    'signed_user_agent' => Str::limit((string) $userAgent, 1000, ''),
                ]);

                $asset->update(['user_id' => $newRecipientId]);

                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'user_id' => $term->issued_by,
                    'action' => 'responsibility_term_signed',
                    'description' => sprintf(
                        '%s assinado por %s (Termo #%d).',
                        $term->type === AssetResponsibilityTerm::TYPE_DELIVERY ? 'Termo de entrega' : 'Termo de devolução',
                        $term->recipient->name,
                        $term->id,
                    ),
                    'old_status' => $asset->status,
                    'new_status' => $asset->status,
                    'old_user_id' => $previousRecipientId,
                    'new_user_id' => $newRecipientId,
                ]);

                return $term->fresh(['asset', 'recipient', 'issuer']);
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($signaturePath);

            throw $exception;
        }
    }

    private function termsText(Asset $asset, User $recipient, string $type): string
    {
        $assetDescription = trim(implode(' ', array_filter([
            $asset->name,
            $asset->brand,
            $asset->model,
        ])));
        $operation = $type === AssetResponsibilityTerm::TYPE_RETURN ? 'devolução' : 'entrega';

        return "TERMO DE RESPONSABILIDADE — {$operation}\n\n"
            . "Eu, {$recipient->name}, confirmo a {$operation} do ativo de TI identificado abaixo:\n"
            . "Ativo: {$assetDescription}\n"
            . "Patrimônio: {$asset->tag}\n"
            . "Número de série: " . ($asset->serial_number ?: 'Não informado') . "\n\n"
            . "Declaro que as informações acima correspondem ao equipamento apresentado. Comprometo-me a utilizar o ativo exclusivamente para fins autorizados, zelar por sua conservação e comunicar à equipe de TI qualquer perda, dano, falha ou uso indevido. Esta assinatura registra minha ciência sobre a movimentação descrita.";
    }
}
