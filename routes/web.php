<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [TicketController::class, 'dashboard'])
        ->name('dashboard');

    // ADMIN DASHBOARD
    Route::get('/admin', [TicketController::class, 'adminDashboard'])
        ->middleware('role:it')
        ->name('admin.dashboard');

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tickets/{id}', [TicketController::class, 'show'])->whereNumber('id')->name('tickets.show');

    // USER
    Route::middleware('role:user')->group(function () {
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my');
    });

    // IT
    Route::middleware('role:it')->group(function () {
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::post('/tickets/{id}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    });

});

require __DIR__.'/auth.php';
