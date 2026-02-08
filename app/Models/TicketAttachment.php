<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_message_id',
        'file_name',
        'file_path',
        'path',
        'filename',
        'mime_type',
        'size',
        'disk',
    ];

    /**
     * Relacionamento com a mensagem do ticket
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(TicketMessage::class, 'ticket_message_id');
    }

    /**
     * Relacionamento com o ticket (através da mensagem)
     */
    public function ticket()
    {
        return $this->hasOneThrough(
            Ticket::class,
            TicketMessage::class,
            'id',
            'id',
            'ticket_message_id',
            'ticket_id'
        );
    }

    /**
     * Verifica se o arquivo é uma imagem
     */
    public function isImage(): bool
    {
        if ($this->mime_type) {
            return str_starts_with($this->mime_type, 'image/');
        }

        // Fallback: verifica pela extensão
        $extension = strtolower(pathinfo($this->filename ?? $this->file_name, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    /**
     * Verifica se é um PDF
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf' || 
               str_ends_with(strtolower($this->filename ?? $this->file_name), '.pdf');
    }

    /**
     * Retorna o tamanho formatado
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size ?? 0;
        
        if ($bytes < 1024) {
            return $bytes . ' B';
        } elseif ($bytes < 1048576) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return round($bytes / 1048576, 2) . ' MB';
        }
    }

    /**
     * Retorna a URL pública do arquivo
     */
    public function getUrlAttribute(): string
    {
        $path = $this->path ?? $this->file_path;
        $disk = $this->disk ?? 'public';

        return Storage::disk($disk)->url($path);
    }

    /**
     * Retorna o nome do arquivo
     */
    public function getNameAttribute(): string
    {
        return $this->filename ?? $this->file_name;
    }

    /**
     * Retorna ícone baseado no tipo de arquivo
     */
    public function getIconAttribute(): string
    {
        if ($this->isImage()) {
            return '🖼️';
        } elseif ($this->isPdf()) {
            return '📄';
        } elseif (str_contains($this->mime_type ?? '', 'zip') || str_contains($this->mime_type ?? '', 'rar')) {
            return '📦';
        } elseif (str_contains($this->mime_type ?? '', 'word') || str_ends_with($this->name, '.doc') || str_ends_with($this->name, '.docx')) {
            return '📝';
        } elseif (str_contains($this->mime_type ?? '', 'excel') || str_ends_with($this->name, '.xls') || str_ends_with($this->name, '.xlsx')) {
            return '📊';
        } else {
            return '📎';
        }
    }
}
