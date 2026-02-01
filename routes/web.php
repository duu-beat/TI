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
    Route::view('/perfil', 'profile.show')->name('profile');

    
    // ✅ Rotas Simplificadas com Resource
    Route::resource('chamados', ClientTicketController::class)
        ->names('tickets')
        ->parameters(['chamados' => 'ticket']) // 👈 O SEGREDO ESTÁ AQUI
        ->except(['edit', 'update', 'destroy']);

    // Rotas específicas que não cabem no Resource
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
        
        // Exemplo de como simplificar o Admin também:
        Route::get('/chamados', [AdminTicketController::class, 'index'])->name('tickets.index');
        Route::get('/chamados/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/chamados/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.update-status');
        Route::post('/chamados/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('tickets.reply');
        Route::get('/relatorio', [AdminTicketController::class, 'report'])->name('tickets.report');
    });
});