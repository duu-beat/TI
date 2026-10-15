<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $sector = $this->route('sector');
        $sectorId = is_object($sector) ? $sector->getKey() : $sector;

        return [
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('sectors', 'name')
                    ->where(fn ($query) => $query->where('branch_id', $this->input('branch_id')))
                    ->ignore($sectorId),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
