<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa um modelo de checklist que será aplicado a chamados de certas categorias
 */
class ChecklistTemplate extends Model
{
    protected $fillable = [
        'category',
        'title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Itens que compõem este modelo de checklist
     */
    public function items(): HasMany
    {
        return $this->hasMany(ChecklistTemplateItem::class, 'template_id')->orderBy('order');
    }
}
