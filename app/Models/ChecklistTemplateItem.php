<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Representa um item individual dentro de um modelo de checklist
 */
class ChecklistTemplateItem extends Model
{
    protected $fillable = [
        'template_id',
        'task',
        'order',
    ];

    /**
     * Modelo ao qual este item pertence
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'template_id');
    }
}
