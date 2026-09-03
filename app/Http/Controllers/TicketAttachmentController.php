<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function show(TicketAttachment $attachment): BinaryFileResponse
    {
        $ticket = $attachment->message?->ticket;
        abort_unless($ticket, 404);
        $this->authorize('view', $ticket);

        return $this->fileResponse($attachment->file_path, $attachment->disk ?? 'local', $attachment->file_name);
    }

    public function thumbnail(TicketAttachment $attachment): BinaryFileResponse
    {
        $ticket = $attachment->message?->ticket;
        abort_unless($ticket, 404);
        $this->authorize('view', $ticket);
        abort_unless($attachment->isImage() && $attachment->thumbnail_path, 404);

        return $this->fileResponse($attachment->thumbnail_path, $attachment->disk ?? 'local', 'thumb-'.$attachment->file_name);
    }

    private function fileResponse(string $path, string $disk, string $downloadName): BinaryFileResponse
    {
        abort_unless(Storage::disk($disk)->exists($path), 404);

        return response()->file(Storage::disk($disk)->path($path), [
            'Content-Disposition' => 'inline; filename="'.addslashes($downloadName).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
