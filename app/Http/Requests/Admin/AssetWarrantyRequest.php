<?php

namespace App\Http\Requests\Admin;

use App\Models\AssetWarranty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetWarrantyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['nullable', 'integer', Rule::exists('suppliers', 'id')->whereNull('deleted_at')],
            'type' => ['required', Rule::in([
                AssetWarranty::TYPE_MANUFACTURER,
                AssetWarranty::TYPE_SUPPLIER,
                AssetWarranty::TYPE_EXTENDED,
            ])],
            'contract_number' => ['nullable', 'string', 'max:100'],
            'invoice_number' => ['nullable', 'string', 'max:100'],
            'starts_at' => ['required', 'date'],
            'expires_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
