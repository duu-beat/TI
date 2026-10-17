<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Branch;
use App\Models\Sector;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class AssetCsvImportService
{
    public const MAX_ROWS = 1000;

    /**
     * @return array{rows: array<int, array<string, mixed>>, errors: array<int, string>, total: int}
     */
    public function validate(UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'rb');
        if ($handle === false) {
            return ['rows' => [], 'errors' => ['Não foi possível abrir o arquivo enviado.'], 'total' => 0];
        }

        $header = fgetcsv($handle, 0, $this->delimiter($file));
        $delimiter = $this->delimiter($file);
        $header = $this->normalizeHeader($header ?: []);
        $required = ['name', 'tag', 'type'];
        $missing = array_values(array_diff($required, $header));
        if ($missing !== []) {
            fclose($handle);
            return ['rows' => [], 'errors' => ['Cabeçalho ausente: ' . implode(', ', $missing) . '.'], 'total' => 0];
        }

        $rows = [];
        $errors = [];
        $seenTags = [];
        $line = 1;
        while (($values = fgetcsv($handle, 0, $delimiter)) !== false) {
            $line++;
            if ($this->isEmptyRow($values)) {
                continue;
            }
            if (count($rows) >= self::MAX_ROWS) {
                $errors[] = "Linha {$line}: o limite de " . self::MAX_ROWS . ' registros por arquivo foi excedido.';
                break;
            }

            $data = $this->mapRow($header, $values);
            $tag = trim((string) ($data['tag'] ?? ''));
            $rowErrors = [];
            if ($tag === '') {
                $rowErrors[] = 'patrimônio (tag) é obrigatório';
            } elseif (isset($seenTags[$tag])) {
                $rowErrors[] = 'patrimônio repetido no arquivo';
            } elseif (Asset::withTrashed()->where('tag', $tag)->exists()) {
                $rowErrors[] = 'patrimônio já cadastrado';
            }
            $seenTags[$tag] = true;

            foreach (['name' => 'nome', 'type' => 'tipo'] as $field => $label) {
                if (trim((string) ($data[$field] ?? '')) === '') {
                    $rowErrors[] = "{$label} é obrigatório";
                }
            }
            if (! in_array($data['status'] ?? 'active', ['active', 'maintenance', 'retired', 'lost'], true)) {
                $rowErrors[] = 'status inválido';
            }

            $user = null;
            if (! empty($data['user_email'])) {
                $user = User::query()->where('email', trim($data['user_email']))->where('role', User::ROLE_CLIENT)->first();
                if (! $user) {
                    $rowErrors[] = 'e-mail do responsável não encontrado';
                }
            }
            $branch = null;
            if (! empty($data['branch_code'])) {
                $branch = Branch::query()->where('code', trim($data['branch_code']))->where('is_active', true)->first();
                if (! $branch) {
                    $rowErrors[] = 'código da filial não encontrado';
                }
            }
            $sector = null;
            if (! empty($data['sector_name'])) {
                if (! $branch) {
                    $rowErrors[] = 'a filial é obrigatória quando o setor é informado';
                } else {
                    $sector = Sector::query()->where('branch_id', $branch->id)->where('name', trim($data['sector_name']))->where('is_active', true)->first();
                    if (! $sector) {
                        $rowErrors[] = 'setor não encontrado na filial informada';
                    }
                }
            }

            $purchaseDate = $this->parseDate($data['purchase_date'] ?? null);
            $warrantyDate = $this->parseDate($data['warranty_expiration'] ?? null);
            if (($data['purchase_date'] ?? '') !== '' && ! $purchaseDate) {
                $rowErrors[] = 'data de compra inválida (use AAAA-MM-DD ou DD/MM/AAAA)';
            }
            if (($data['warranty_expiration'] ?? '') !== '' && ! $warrantyDate) {
                $rowErrors[] = 'data de garantia inválida (use AAAA-MM-DD ou DD/MM/AAAA)';
            }
            if ($purchaseDate && $warrantyDate && $warrantyDate->lt($purchaseDate)) {
                $rowErrors[] = 'garantia não pode terminar antes da compra';
            }

            if ($rowErrors !== []) {
                $errors[] = 'Linha ' . $line . ': ' . implode('; ', $rowErrors) . '.';
                continue;
            }

            $rows[] = [
                'name' => trim($data['name']),
                'tag' => $tag,
                'type' => trim($data['type']),
                'brand' => $this->nullable($data['brand'] ?? null),
                'model' => $this->nullable($data['model'] ?? null),
                'serial_number' => $this->nullable($data['serial_number'] ?? null),
                'status' => $data['status'] ?: 'active',
                'purchase_date' => $purchaseDate?->toDateString(),
                'warranty_expiration' => $warrantyDate?->toDateString(),
                'notes' => $this->nullable($data['notes'] ?? null),
                'user_id' => $user?->id,
                'branch_id' => $branch?->id,
                'sector_id' => $sector?->id,
            ];
        }
        fclose($handle);

        return ['rows' => $rows, 'errors' => $errors, 'total' => count($rows) + count($errors)];
    }

    private function delimiter(UploadedFile $file): string
    {
        $line = (string) @file($file->getRealPath(), FILE_IGNORE_NEW_LINES)[0];
        return substr_count($line, ';') > substr_count($line, ',') ? ';' : ',';
    }

    private function normalizeHeader(array $header): array
    {
        return array_map(function ($value): string {
            $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value);
            return strtolower(trim((string) $value));
        }, $header);
    }

    private function mapRow(array $header, array $values): array
    {
        $values = array_pad($values, count($header), null);
        return array_combine($header, array_slice($values, 0, count($header))) ?: [];
    }

    private function parseDate(?string $value): ?Carbon
    {
        $value = trim((string) $value);
        if ($value === '') return null;
        foreach (['Y-m-d', 'd/m/Y'] as $format) {
            try {
                $date = Carbon::createFromFormat('!' . $format, $value);
                if ($date && $date->format($format) === $value) return $date;
            } catch (\Throwable) { }
        }
        return null;
    }

    private function nullable(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function isEmptyRow(array $values): bool
    {
        return count(array_filter($values, fn ($value) => trim((string) $value) !== '')) === 0;
    }
}
