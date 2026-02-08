<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Urgente',
                'slug' => 'urgente',
                'color' => '#EF4444',
                'description' => 'Problemas que requerem atenção imediata',
            ],
            [
                'name' => 'Hardware',
                'slug' => 'hardware',
                'color' => '#3B82F6',
                'description' => 'Problemas relacionados a equipamentos físicos',
            ],
            [
                'name' => 'Software',
                'slug' => 'software',
                'color' => '#8B5CF6',
                'description' => 'Problemas com aplicativos e sistemas',
            ],
            [
                'name' => 'Rede',
                'slug' => 'rede',
                'color' => '#10B981',
                'description' => 'Problemas de conectividade e rede',
            ],
            [
                'name' => 'E-mail',
                'slug' => 'email',
                'color' => '#F59E0B',
                'description' => 'Problemas com e-mail e comunicação',
            ],
            [
                'name' => 'Impressora',
                'slug' => 'impressora',
                'color' => '#6366F1',
                'description' => 'Problemas com impressoras e periféricos',
            ],
            [
                'name' => 'Acesso',
                'slug' => 'acesso',
                'color' => '#EC4899',
                'description' => 'Problemas de login e permissões',
            ],
            [
                'name' => 'Treinamento',
                'slug' => 'treinamento',
                'color' => '#14B8A6',
                'description' => 'Solicitações de treinamento ou dúvidas',
            ],
            [
                'name' => 'Bug',
                'slug' => 'bug',
                'color' => '#DC2626',
                'description' => 'Erros ou falhas no sistema',
            ],
            [
                'name' => 'Melhoria',
                'slug' => 'melhoria',
                'color' => '#059669',
                'description' => 'Sugestões de melhorias',
            ],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
