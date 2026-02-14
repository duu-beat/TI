<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketMessage extends Model
{
    protected $fillable = [
        'ticket_id', 
        'user_id', 
        'message', 
        'is_internal'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        // Se user_id for NULL, retorna um objeto fake de IA
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'IA Assistant',
            'email' => 'ai@suporte.ti',
            'role' => 'system',
            'profile_photo_url' => 'https://ui-avatars.com/api/?name=AI&background=6366f1&color=fff&font-size=0.5'
        ]);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }
}