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
            // Validação adicional
            if (!$file->isValid()) {
                continue;
            }

            // Gerar nome único
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Salvar arquivo
            $path = $file->storeAs('ticket-attachments', $filename, 'public');

            // Criar registro com informações detalhadas
            $message->attachments()->create([
                'filename' => $file->getClientOriginalName(),
                'file_name' => $file->getClientOriginalName(),
                'path' => $path,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
            ]);
        }
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
        $path = $attachment->path ?? $attachment->file_path;
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
