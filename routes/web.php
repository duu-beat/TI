<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

// Controllers
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Master\AuthController as MasterAuthController;
use App\Http\Controllers\Master\DashboardController as MasterDashboardController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LegalController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Admin\CannedResponseController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
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
| LOGIN SOCIAL (Google / GitHub)
|--------------------------------------------------------------------------
*/
Route::get('/auth/{provider}/redirect', function ($provider) {
    return Socialite::driver($provider)->redirect();
})->name('social.redirect');

Route::get('/auth/{provider}/callback', function ($provider) {
    try {
        $socialUser = Socialite::driver($provider)->user();
        
        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getName(),
            // Gera uma senha aleatória segura se for um novo utilizador
            'password' => Auth::check() ? Auth::user()->password : Hash::make(Str::random(24)),
        ]);
    
        Auth::login($user);
    
        return redirect()->route('client.dashboard');
    } catch (\Exception $e) {
        return redirect()->route('login')->with('status', 'Erro ao logar com ' . ucfirst($provider));
    }
})->name('social.callback');

/*
|--------------------------------------------------------------------------
| ÁREA DO CLIENTE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('cliente')->name('client.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/perfil', 'profile.show')->name('profile');
    Route::get('/faq', [\App\Http\Controllers\Client\FaqController::class, 'index'])->name('faq');

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

    Route::middleware(['auth', 'verified', 'admin'])->group(function () {
        
        Route::get('/dashboard', [AdminTicketController::class, 'dashboard'])->name('dashboard');
        Route::view('/perfil', 'profile.show')->name('profile');
        
        // ✅ ROTA NOVA: Busca Global
        Route::get('/global-search', [\App\Http\Controllers\Admin\TicketController::class, 'globalSearch'])
            ->name('global-search');
        
        // 1. CRUD de Respostas Prontas
        Route::resource('respostas-prontas', CannedResponseController::class)
            ->parameters(['respostas-prontas' => 'cannedResponse']);

        // 2. Rotas de Chamados
        Route::controller(AdminTicketController::class)
            ->prefix('chamados')
            ->name('tickets.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                
                // ✅ ROTA KANBAN (Nova)
                Route::get('/kanban', 'kanban')->name('kanban');

                Route::get('/relatorio', 'report')->name('report');
                
                Route::prefix('{ticket}')->group(function () {
                    Route::get('/', 'show')->name('show');
                    Route::patch('/status', 'updateStatus')->name('update-status');
                    Route::post('/responder', 'reply')->name('reply');
                    Route::post('/escalar', 'escalate')->name('escalate');
                    Route::patch('/atribuir', 'assign')->name('assign');
                    Route::post('/fundir', 'merge')->name('merge');
                });
            });
    });
});

/*
|--------------------------------------------------------------------------
| ÁREA DE SEGURANÇA (MASTER)
|--------------------------------------------------------------------------
*/
Route::prefix('seguranca')->name('master.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [MasterAuthController::class, 'create'])->name('login');
        Route::post('/login', [MasterAuthController::class, 'store'])->name('login.store');
    });
    Route::post('/logout', [MasterAuthController::class, 'destroy'])->name('logout');
});

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

        Route::post('/chamados/{ticket}/resolver-master', [MasterDashboardController::class, 'resolveEscalated'])->name('tickets.resolve');
        Route::get('/logs-sistema', [MasterDashboardController::class, 'systemLogs'])->name('system-logs');
        Route::post('/logs-sistema/limpar', [MasterDashboardController::class, 'clearSystemLogs'])->name('system-logs.clear');
});

// Rotas Extras (Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    
    Route::post('chamados/{ticket}/tags', [\App\Http\Controllers\Admin\TagController::class, 'attachToTicket'])
        ->name('tickets.tags.attach');

    Route::get('/relatorios', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/relatorios/exportar-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/relatorios/exportar-excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.export-excel');
});