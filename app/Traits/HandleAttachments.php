<?php

namespace App\Traits;

use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait HandleAttachments
{
    /**
     * Processa o upload de anexos para uma mensagem ou ticket.
     *
     * @param Request $request
     * @param Model $message (TicketMessage)
     */
    protected function processAttachments(Request $request, Model $message): void
    {
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                TicketAttachment::create([
                    'ticket_message_id' => $message->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }
    }
}