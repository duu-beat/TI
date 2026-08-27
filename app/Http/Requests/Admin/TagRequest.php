<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $tag = $this->route('tag');
        $tagId = is_object($tag) ? $tag->getKey() : $tag;

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('tags', 'name')->ignore($tagId)],
            'color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
