<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Escopo para busca em múltiplos campos
     */
    public function scopeSearch(Builder $query, string $term, array $columns = []): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term, $columns) {
            $this->applySearchFilters($query, $term, $columns);
        });
    }

    /**
     * Escopo para busca OR em múltiplos campos
     */
    public function scopeOrSearch(Builder $query, string $term, array $columns = []): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->orWhere(function (Builder $query) use ($term, $columns) {
            $this->applySearchFilters($query, $term, $columns);
        });
    }

    /**
     * Aplica os filtros de busca à query
     */
    protected function applySearchFilters(Builder $query, string $term, array $columns): void
    {
        foreach ($columns as $column) {
            if (str_contains($column, '.')) {
                $parts = explode('.', $column);
                $field = array_pop($parts);
                $relation = implode('.', $parts);

                $query->orWhereHas($relation, function (Builder $query) use ($field, $term) {
                    $query->where($field, 'like', "%{$term}%");
                });
            } else {
                $query->orWhere($column, 'like', "%{$term}%");
            }
        }
    }
}
