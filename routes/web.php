<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Master\AuthController as MasterAuthController;
use App\Http\Controllers\Master\DashboardController as MasterDashboardController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\LegalController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Admin\CannedResponseController; // ✅ Importado

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::view('/', 'public.home')->name('home');
Route::view('/servicos', 'public.services')->name('services');
Route::view('/portfolio', 'public.portfolio')->name('portfolio');
Route::view('/sobre', 'public.sobre')->name('sobre');

Route::get('/contato', [ContactController::class, 'index'])->name('contact');
Route::post('/contato', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::get('/termos-de-uso', [LegalController::class, 'terms'])->name('terms');
Route::get('/privacidade', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/sla', [LegalController::class, 'sla'])->name('sla');

/*
|--------------------------------------------------------------------------
| ÁREA DO CLIENTE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('cliente')->name('client.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/perfil', 'profile.show')->name('profile');

    Route::resource('chamados', ClientTicketController::class)
        ->names('tickets')
        ->parameters(['chamados' => 'ticket'])
        ->except(['edit', 'update', 'destroy']);

    Route::controller(ClientTicketController::class)->prefix('chamados/{ticket}')->name('tickets.')->group(function () {
        Route::post('/responder', 'reply')->name('reply');
        Route::post('/avaliar', 'rate')->name('rate');
    });
});

/*
|--------------------------------------------------------------------------
| ÁREA DO ADMINISTRADOR
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::redirect('/', '/admin/login');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    // Middleware 'admin' agora permite Master também
    Route::middleware(['auth', 'verified', 'admin'])->group(function () {
        
        Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('dashboard');
        Route::view('/perfil', 'profile.show')->name('profile');
        
        // ✅ 1. CRUD de Respostas Prontas
        Route::resource('respostas-prontas', CannedResponseController::class)
            ->parameters(['respostas-prontas' => 'cannedResponse']);

        // ✅ 2. Rotas de Chamados (Com as novas funções)
        Route::controller(AdminTicketController::class)
            ->prefix('chamados')
            ->name('tickets.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/relatorio', 'report')->name('report');
                
                Route::prefix('{ticket}')->group(function () {
                    Route::get('/', 'show')->name('show');
                    Route::patch('/status', 'updateStatus')->name('update-status');
                    
                    // Ação de Responder (suporta nota interna agora)
                    Route::post('/responder', 'reply')->name('reply');
                    
                    // Escalonar
                    Route::post('/escalar', 'escalate')->name('escalate');

                    // ✅ Novas Ações: Atribuir e Fundir
                    Route::patch('/atribuir', 'assign')->name('assign');
                    Route::post('/fundir', 'merge')->name('merge');
                });
            });
    });
});

/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO DE SEGURANÇA (URL: /seguranca/login)
|--------------------------------------------------------------------------
*/
Route::prefix('seguranca')->name('master.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [MasterAuthController::class, 'create'])->name('login');
        Route::post('/login', [MasterAuthController::class, 'store'])->name('login.store');
    });
    Route::post('/logout', [MasterAuthController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PAINEL DE SEGURANÇA (URL: /seguranca)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', \App\Http\Middleware\MasterMiddleware::class])
    ->prefix('seguranca')
    ->name('master.')
    ->group(function () {
        
        Route::get('/', [MasterDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/auditoria', [MasterDashboardController::class, 'audit'])->name('audit');
        Route::get('/configuracoes', [MasterDashboardController::class, 'settings'])->name('settings');
        Route::post('/configuracoes', [MasterDashboardController::class, 'updateSettings'])->name('settings.update');
        
        Route::resource('usuarios', \App\Http\Controllers\Master\UserController::class)
        ->names('users')
        ->parameters(['usuarios' => 'user'])
        ->only(['index', 'store', 'destroy', 'update']);
        
        Route::view('/perfil', 'profile.show')->name('profile');
        Route::patch('/usuarios/{user}/toggle-admin', [MasterDashboardController::class, 'toggleAdmin'])->name('users.toggle-admin');

        Route::post('/chamados/{ticket}/resolver-master', [MasterDashboardController::class, 'resolveEscalated'])
            ->name('tickets.resolve');

        Route::get('/logs-sistema', [MasterDashboardController::class, 'systemLogs'])->name('system-logs');
        Route::post('/logs-sistema/limpar', [MasterDashboardController::class, 'clearSystemLogs'])->name('system-logs.clear');
});
/*
|--------------------------------------------------------------------------
| ROTAS ADICIONADAS - MELHORIAS
|--------------------------------------------------------------------------
*/

// Rotas de Tags (Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    
    Route::post('chamados/{ticket}/tags', [\App\Http\Controllers\Admin\TagController::class, 'attachToTicket'])
        ->name('tickets.tags.attach');
});

// Rotas de Relatórios (Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/relatorios', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
        ->name('reports.index');
    
    Route::get('/relatorios/exportar-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])
        ->name('reports.export-pdf');
    
    Route::get('/relatorios/exportar-excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])
        ->name('reports.export-excel');
});
