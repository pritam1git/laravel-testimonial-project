<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CrudController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CrudController::class, 'public'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [CrudController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [CrudController::class, 'store'])->name('testimonials.store');
    Route::put('/update/{id}', [CrudController::class, 'update'])->name('testimonials.update');
    Route::delete('/dashboard/{id}', [CrudController::class, 'destroy'])->name('testimonials.destroy');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
