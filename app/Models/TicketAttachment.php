<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketAttachment extends Model
{
    protected $fillable = ['ticket_message_id', 'file_path', 'file_name'];

    // Acessor para pegar a URL completa facilmente
    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}