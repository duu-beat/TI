<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        // FAQ estruturado por categorias
        $faqs = $this->getFaqData();

        // Filtrar por busca
        if ($search) {
            $faqs = collect($faqs)->map(function ($category) use ($search) {
                $category['items'] = collect($category['items'])->filter(function ($item) use ($search) {
                    return str_contains(strtolower($item['question']), strtolower($search)) ||
                           str_contains(strtolower($item['answer']), strtolower($search));
                })->values()->all();
                return $category;
            })->filter(function ($category) {
                return count($category['items']) > 0;
            })->values()->all();
        }

        // Filtrar por categoria
        if ($category) {
            $faqs = collect($faqs)->filter(function ($cat) use ($category) {
                return $cat['slug'] === $category;
            })->values()->all();
        }

        $categories = $this->getCategories();

        return view('client.faq', compact('faqs', 'categories', 'search', 'category'));
    }

    private function getFaqData()
    {
        return [
            [
                'name' => 'Hardware',
                'slug' => 'hardware',
                'icon' => '🖥️',
                'color' => 'blue',
                'items' => [
                    [
                        'question' => 'Meu computador está lento, o que fazer?',
                        'answer' => 'Primeiro, verifique se há programas desnecessários rodando em segundo plano. Abra o Gerenciador de Tarefas (Ctrl+Shift+Esc) e feche aplicativos que não está usando. Se o problema persistir, abra um chamado para que possamos verificar se há necessidade de limpeza ou upgrade de hardware.'
                    ],
                    [
                        'question' => 'Como solicitar um novo equipamento?',
                        'answer' => 'Abra um chamado selecionando a categoria "Hardware" e descreva qual equipamento você precisa e o motivo. Nossa equipe irá avaliar a solicitação e responder em até 24 horas úteis.'
                    ],
                    [
                        'question' => 'Meu teclado ou mouse não está funcionando',
                        'answer' => 'Verifique se os cabos estão bem conectados. Para dispositivos sem fio, troque as pilhas. Se o problema persistir, abra um chamado e podemos providenciar a substituição.'
                    ],
                ]
            ],
            [
                'name' => 'Software',
                'slug' => 'software',
                'icon' => '💻',
                'color' => 'purple',
                'items' => [
                    [
                        'question' => 'Como instalar um novo programa?',
                        'answer' => 'Por questões de segurança, apenas a equipe de TI pode instalar novos programas. Abra um chamado informando qual software você precisa e para qual finalidade. Avaliaremos e instalaremos se aprovado.'
                    ],
                    [
                        'question' => 'Esqueci minha senha do Windows',
                        'answer' => 'Abra um chamado urgente selecionando prioridade "Alta". Nossa equipe irá redefinir sua senha remotamente ou presencialmente, dependendo da situação.'
                    ],
                    [
                        'question' => 'Preciso de acesso a um sistema específico',
                        'answer' => 'Abra um chamado informando qual sistema você precisa acessar e qual sua função/departamento. Após aprovação do seu gestor, liberaremos o acesso em até 48 horas.'
                    ],
                ]
            ],
            [
                'name' => 'Rede e Internet',
                'slug' => 'rede',
                'icon' => '🌐',
                'color' => 'green',
                'items' => [
                    [
                        'question' => 'Não consigo conectar à internet',
                        'answer' => 'Verifique se o cabo de rede está conectado ou se o Wi-Fi está ativado. Tente reiniciar o computador. Se não resolver, abra um chamado urgente para que possamos verificar.'
                    ],
                    [
                        'question' => 'Como conectar ao Wi-Fi da empresa?',
                        'answer' => 'Procure pela rede "NomeDaEmpresa-WiFi" nas redes disponíveis. A senha é fornecida no seu primeiro dia. Se não tiver a senha, abra um chamado.'
                    ],
                    [
                        'question' => 'A internet está muito lenta',
                        'answer' => 'Isso pode ser temporário devido ao alto uso da rede. Se persistir por mais de 30 minutos, abra um chamado para investigarmos possíveis problemas de conexão ou configuração.'
                    ],
                ]
            ],
            [
                'name' => 'E-mail',
                'slug' => 'email',
                'icon' => '📧',
                'color' => 'red',
                'items' => [
                    [
                        'question' => 'Não consigo enviar ou receber e-mails',
                        'answer' => 'Verifique sua conexão com a internet primeiro. Se estiver conectado, tente fechar e abrir o cliente de e-mail novamente. Se o problema persistir, abra um chamado urgente.'
                    ],
                    [
                        'question' => 'Como configurar e-mail no celular?',
                        'answer' => 'Abra um chamado e nossa equipe irá te orientar passo a passo ou fazer a configuração remotamente, se possível.'
                    ],
                    [
                        'question' => 'Recebi um e-mail suspeito, o que fazer?',
                        'answer' => 'NÃO clique em links ou baixe anexos. Marque como spam e abra um chamado imediatamente informando o remetente e assunto. Nossa equipe de segurança irá investigar.'
                    ],
                ]
            ],
            [
                'name' => 'Impressoras',
                'slug' => 'impressora',
                'icon' => '🖨️',
                'color' => 'yellow',
                'items' => [
                    [
                        'question' => 'A impressora não está imprimindo',
                        'answer' => 'Verifique se há papel e se não há atolamento. Confira se a impressora está ligada e conectada à rede. Se tudo estiver ok, abra um chamado.'
                    ],
                    [
                        'question' => 'Como adicionar uma impressora no meu computador?',
                        'answer' => 'Abra um chamado e nossa equipe irá configurar a impressora remotamente ou presencialmente.'
                    ],
                    [
                        'question' => 'A impressão está saindo com qualidade ruim',
                        'answer' => 'Pode ser falta de toner/tinta. Abra um chamado para que possamos verificar e providenciar a substituição se necessário.'
                    ],
                ]
            ],
        ];
    }

    private function getCategories()
    {
        return [
            ['name' => 'Hardware', 'slug' => 'hardware', 'icon' => '🖥️', 'color' => 'blue'],
            ['name' => 'Software', 'slug' => 'software', 'icon' => '💻', 'color' => 'purple'],
            ['name' => 'Rede e Internet', 'slug' => 'rede', 'icon' => '🌐', 'color' => 'green'],
            ['name' => 'E-mail', 'slug' => 'email', 'icon' => '📧', 'color' => 'red'],
            ['name' => 'Impressoras', 'slug' => 'impressora', 'icon' => '🖨️', 'color' => 'yellow'],
        ];
    }
}
