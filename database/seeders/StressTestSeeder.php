<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketMessage;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StressTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar Usuários (100 usuários)
        $this->command->info('Criando 100 usuários...');
        $users = User::factory(100)->create(['role' => 'client']);
        
        // 2. Criar Admins (10 admins)
        $this->command->info('Criando 10 admins...');
        $admins = User::factory(10)->create(['role' => 'admin']);

        // 3. Criar Ativos (200 ativos)
        $this->command->info('Criando 200 ativos...');
        $assets = Asset::factory(200)->create([
            'user_id' => fn() => $users->random()->id
        ]);

        // 4. Criar Chamados em Massa (2000 chamados)
        $this->command->info('Criando 2000 chamados...');
        
        $statuses = TicketStatus::cases();
        $priorities = TicketPriority::cases();

        for ($i = 0; $i < 20; $i++) { // 20 blocos de 100 para evitar estouro de memória
            $ticketsData = [];
            for ($j = 0; $j < 100; $j++) {
                $ticketsData[] = [
                    'user_id' => $users->random()->id,
                    'asset_id' => $assets->random()->id,
                    'assigned_to' => $admins->random()->id,
                    'category' => 'Hardware',
                    'subject' => 'Chamado de Teste de Estresse #' . ($i * 100 + $j),
                    'description' => 'Descrição detalhada para teste de performance do banco de dados.',
                    'status' => $statuses[array_rand($statuses)]->value,
                    'priority' => $priorities[array_rand($priorities)]->value,
                    'sla_due_at' => now()->addHours(rand(-48, 72)),
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ];
            }
            DB::table('tickets')->insert($ticketsData);
        }

        $this->command->info('Seed de estresse concluído com sucesso!');
    }
}
