<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
    ];

    /**
     * Boot do modelo para gerar slug automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relacionamento polimórfico com tickets
     */
    public function tickets(): MorphToMany
    {
        return $this->morphedByMany(Ticket::class, 'taggable');
    }

    /**
     * Scope para buscar por nome ou slug
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
    }

    /**
     * Retorna a cor formatada para uso em CSS
     */
    public function getColorClassAttribute(): string
    {
        return "bg-[{$this->color}]";
    }
}
