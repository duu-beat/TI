<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    public function index()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'queue' => $this->checkQueue(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        return view('master.health', compact('health'));
    }

    protected function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok', 'message' => 'Conexão com banco de dados estabelecida com sucesso.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro ao conectar ao banco: ' . $e->getMessage()];
        }
    }

    protected function checkQueue(): array
    {
        try {
            $connection = config('queue.default');
            $size = DB::table('jobs')->count();
            return ['status' => 'ok', 'message' => "Driver [{$connection}] ativo. Trabalhos pendentes na fila: {$size}"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro ao verificar filas: ' . $e->getMessage()];
        }
    }

    protected function checkStorage(): array
    {
        try {
            Storage::disk('public')->put('health-check.txt', 'test');
            Storage::disk('public')->delete('health-check.txt');
            return ['status' => 'ok', 'message' => 'Disco de armazenamento (public) gravável e acessível.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro no armazenamento: ' . $e->getMessage()];
        }
    }

    protected function checkCache(): array
    {
        try {
            Cache::put('health_test', 'ok', 10);
            $val = Cache::get('health_test');
            Cache::forget('health_test');
            return ['status' => 'ok', 'message' => $val === 'ok' ? 'Driver de cache funcionando perfeitamente.' : 'Falha na leitura/escrita do cache.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Erro no cache: ' . $e->getMessage()];
        }
    }
}
