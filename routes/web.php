<?php

use App\Http\Controllers\DocController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect('/admin/login');
    }

    return auth()->user()->role === 'sales'
        ? redirect('/employee')
        : redirect('/admin');
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/admin/login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/docs/proforma/{company}', [DocController::class, 'proforma'])->name('docs.proforma');
    Route::get('/docs/contract/{company}', [DocController::class, 'contract'])->name('docs.contract');
});
