<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class SafeImage implements ValidationRule
{
    public function __construct(private readonly int $maxWidth = 6000, private readonly int $maxHeight = 6000)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return;
        }

        if (! str_starts_with((string) $value->getMimeType(), 'image/')) {
            return;
        }

        $size = @getimagesize($value->getRealPath());
        if ($size === false) {
            $fail('O arquivo de imagem não possui conteúdo válido.');
            return;
        }

        if ($size[0] > $this->maxWidth || $size[1] > $this->maxHeight) {
            $fail("A imagem não pode ultrapassar {$this->maxWidth}x{$this->maxHeight} pixels.");
        }
    }
}
