<?php

namespace App\Traits;

use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandleAttachmentsEnhanced
{
    /**
     * Processa anexos com informações detalhadas
     */
    protected function processAttachmentsEnhanced(Request $request, TicketMessage $message): void
    {
        if (!$request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $file) {
            if (! $file->isValid()) {
                continue;
            }

            // O armazenamento gera um nome aleatório no servidor, sem reutilizar o nome enviado pelo usuário.
            $disk = 'local';
            $path = $file->store('ticket-attachments', $disk);
            $thumbnailPath = $this->createImageThumbnail($file->getRealPath(), $file->getMimeType(), $disk);

            $message->attachments()->create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => $disk,
            ]);
        }
    }

    /**
     * Gera uma miniatura privada para imagens, reduzindo o custo de carregamento nas telas.
     */
    private function createImageThumbnail(string $sourcePath, ?string $mimeType, string $disk): ?string
    {
        if (! str_starts_with((string) $mimeType, 'image/') || ! function_exists('imagecreatefromstring')) {
            return null;
        }

        $contents = @file_get_contents($sourcePath);
        $source = $contents !== false ? @imagecreatefromstring($contents) : false;
        if (! $source) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $max = 1200;
        $scale = min(1, $max / max($width, $height));
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
        $background = imagecolorallocate($thumbnail, 255, 255, 255);
        imagefill($thumbnail, 0, 0, $background);
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($thumbnail, null, 82);
        $encoded = ob_get_clean();
        imagedestroy($source);
        imagedestroy($thumbnail);

        if (! is_string($encoded) || $encoded === '') {
            return null;
        }

        $thumbnailPath = 'ticket-attachments/thumbs/'.bin2hex(random_bytes(16)).'.jpg';
        Storage::disk($disk)->put($thumbnailPath, $encoded);

        return $thumbnailPath;
    }

    /**
     * Remove anexo e arquivo do storage
     */
    protected function deleteAttachment($attachmentId): bool
    {
        $attachment = \App\Models\TicketAttachment::find($attachmentId);

        if (!$attachment) {
            return false;
        }

        // Deletar arquivo físico
        $path = $attachment->file_path;
        $disk = $attachment->disk ?? 'public';

        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }

        // Deletar registro
        $attachment->delete();

        return true;
    }

    /**
     * Valida tipos de arquivo permitidos
     */
    protected function validateAttachmentType($file): bool
    {
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/x-rar-compressed',
            'text/plain',
        ];

        return in_array($file->getMimeType(), $allowedMimes);
    }

    /**
     * Retorna tamanho máximo permitido em bytes
     */
    protected function getMaxFileSize(): int
    {
        return 10 * 1024 * 1024; // 10MB
    }
}
