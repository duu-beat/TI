<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

Route::view('/', 'public.home')->name('home');
Route::view('/servicos', 'public.services')->name('services');
Route::view('/portfolio', 'public.portfolio')->name('portfolio');
Route::view('/contato', 'public.contact')->name('contact');

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
 * ADMIN (login próprio em /admin)
 * - se não estiver logado: mostra auth.admin-login
 * - se estiver logado e for admin: manda pro dashboard
 * - se estiver logado e NÃO for admin: 403
 */
Route::get('/admin', function () {
    if (!Auth::check()) {
        return view('auth.admin-login');
    }

    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    return redirect()->route('admin.dashboard');
})->name('admin.login');

/**
 * ADMIN (área protegida)
 */
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::get('/chamados', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/chamados/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/chamados/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.status');
    Route::post('/chamados/{ticket}/responder', [AdminTicketController::class, 'reply'])->name('tickets.reply');
});
