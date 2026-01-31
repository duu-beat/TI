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

    // ✅ ADICIONE ESTA LINHA (Rota de Perfil do Cliente)
    // Isso cria a rota: /app/perfil (nome: client.profile)
    Route::view('/perfil', 'profile.show')->name('profile');

    // Chamados
    Route::get('/chamados', [ClientTicketController::class, 'index'])->name('tickets.index');
    Route::get('/chamados/criar', [ClientTicketController::class, 'create'])->name('tickets.create');
    Route::post('/chamados', [ClientTicketController::class, 'store'])->name('tickets.store');
    Route::get('/chamados/{ticket}', [ClientTicketController::class, 'show'])->name('tickets.show');
    Route::post('/chamados/{ticket}/responder', [ClientTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/chamados/{ticket}/avaliar', [ClientTicketController::class, 'rate'])->name('tickets.rate');
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
        
        // ✅ ADICIONE ESTA LINHA (Rota de Perfil do Admin)
        // Isso cria a rota: /admin/perfil (nome: admin.profile)
        Route::view('/perfil', 'profile.show')->name('profile');
        
        Route::get('/chamados', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/chamados/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/chamados/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
        Route::post('/chamados/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::get('/relatorio', [AdminTicketController::class, 'report'])->name('tickets.report');
    });
});