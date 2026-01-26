<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController; // ✅ Novo import
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\LegalController;

Route::view('/', 'public.home')->name('home');
Route::view('/servicos', 'public.services')->name('services');
Route::view('/portfolio', 'public.portfolio')->name('portfolio');
Route::view('/contato', 'public.contact')->name('contact');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Páginas Legais
Route::get('/termos-de-uso', [LegalController::class, 'terms'])->name('terms');
Route::get('/privacidade', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/sla', [LegalController::class, 'sla'])->name('sla');



/**
 * CLIENTE
 */
Route::middleware(['auth', 'verified'])->prefix('app')->name('client.')->group(function () {
    Route::view('/', 'client.dashboard')->name('dashboard');

    Route::get('/chamados', [ClientTicketController::class, 'index'])->name('tickets.index');
    Route::get('/chamados/criar', [ClientTicketController::class, 'create'])->name('tickets.create');
    Route::post('/chamados', [ClientTicketController::class, 'store'])->name('tickets.store');
    Route::get('/chamados/{ticket}', [ClientTicketController::class, 'show'])->name('tickets.show');
    Route::post('/chamados/{ticket}/responder', [ClientTicketController::class, 'reply'])->name('tickets.reply');
});

/**
 * ADMIN - Autenticação e Painel
 */
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 1. Rota Raiz: Ao acessar "/admin", redireciona para o login
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // 2. Rotas de Autenticação (Ficam em /admin/login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    // 3. Logout
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

    // 4. Área Protegida
    Route::middleware(['auth', 'verified', 'admin'])->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        
        // Rotas de Chamados
        Route::get('/chamados', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/chamados/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::post('/chamados/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');
        Route::post('/chamados/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::get('/relatorio', [AdminTicketController::class, 'report'])->name('tickets.report');
    });
});
