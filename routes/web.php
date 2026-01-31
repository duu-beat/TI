<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\LegalController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Public\ContactController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Site Institucional)
|--------------------------------------------------------------------------
| Estas rotas estão acessíveis a qualquer visitante sem login.
| Usamos Route::view para páginas estáticas (sem lógica) e Controllers para
| páginas com formulários ou dados dinâmicos.
*/

Route::view('/', 'public.home')->name('home');             // Página Inicial
Route::view('/servicos', 'public.services')->name('services'); // Lista de Serviços
Route::view('/portfolio', 'public.portfolio')->name('portfolio'); // Portfólio
Route::view('/sobre', 'public.sobre')->name('sobre');      // Página Sobre Nós

// Contato (Exibe o formulário e Processa o envio)
Route::get('/contato', [ContactController::class, 'index'])->name('contact');
Route::post('/contato', [ContactController::class, 'submit'])->name('contact.submit');

// FAQ (Perguntas Frequentes - Busca dados do banco)
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

/*
|--------------------------------------------------------------------------
| Páginas Legais
|--------------------------------------------------------------------------
| Termos, Privacidade e SLA. Importante para conformidade e transparência.
*/
Route::get('/termos-de-uso', [LegalController::class, 'terms'])->name('terms');
Route::get('/privacidade', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/sla', [LegalController::class, 'sla'])->name('sla');

/*
|--------------------------------------------------------------------------
| ÁREA DO CLIENTE (Requer Login)
|--------------------------------------------------------------------------
| Middleware 'auth': Só utilizadores logados.
| Middleware 'verified': Só emails verificados.
| Prefix 'app': Todas as URLs começam com /app (ex: /app/chamados).
| Name 'client.': Nomes das rotas começam com client (ex: client.dashboard).
*/
Route::middleware(['auth', 'verified'])->prefix('app')->name('client.')->group(function () {
    
    // Painel Principal do Cliente (Resumo dos tickets)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestão de Chamados (Tickets)
    Route::get('/chamados', [ClientTicketController::class, 'index'])->name('tickets.index');          // Listar
    Route::get('/chamados/criar', [ClientTicketController::class, 'create'])->name('tickets.create');  // Formulário de Criação
    Route::post('/chamados', [ClientTicketController::class, 'store'])->name('tickets.store');         // Salvar Novo Ticket
    Route::get('/chamados/{ticket}', [ClientTicketController::class, 'show'])->name('tickets.show');   // Ver Detalhes
    
    // Interações no Chamado
    Route::post('/chamados/{ticket}/responder', [ClientTicketController::class, 'reply'])->name('tickets.reply'); // Enviar Resposta
    Route::post('/chamados/{ticket}/avaliar', [ClientTicketController::class, 'rate'])->name('tickets.rate');     // Avaliar Atendimento
});

/*
|--------------------------------------------------------------------------
| ÁREA DO ADMINISTRADOR
|--------------------------------------------------------------------------
| Prefix 'admin': Todas as URLs começam com /admin.
| Name 'admin.': Nomes das rotas começam com admin.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 1. Redirecionamento da Raiz Admin
    // ✅ MELHORIA: Usar Route::redirect em vez de Closure permite cache de rotas (php artisan route:cache)
    // Se acessar /admin, vai para /admin/login
    Route::redirect('/', '/admin/login');

    // 2. Autenticação (Apenas para visitantes/não logados)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login'); // Form de Login
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store'); // Processar Login
    });

    // 3. Logout (Acessível a qualquer logado)
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    // 4. Painel Protegido (Requer login E ser admin)
    Route::middleware(['auth', 'verified', 'admin'])->group(function () {
        
        // Dashboard Administrativo (Gráficos e KPIs)
        Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('dashboard');
        
        // Gestão de Chamados
        Route::get('/chamados', [AdminTicketController::class, 'index'])->name('tickets.index');        // Listagem Geral
        Route::get('/chamados/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show'); // Ver Detalhes
        
        // Ações Administrativas
        // Atualiza o status (Aberto -> Em Andamento -> Resolvido)
        Route::patch('/chamados/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
        
        // Responder ao cliente
        Route::post('/chamados/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        
        // Relatórios
        Route::get('/relatorio', [AdminTicketController::class, 'report'])->name('tickets.report');
    });
});