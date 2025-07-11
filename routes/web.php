<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\TestimonialController;

// Public routes
Route::get('/', [CrudController::class, 'public'])->name('home');

Route::get('/client-review', [ReviewController::class, 'showFrontend'])->name('reviews');
Route::post('/client-review/store', [ReviewController::class, 'storeFrontend'])->name('review.store');

// Admin authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard Review Management
    Route::get('/dashboard', [ReviewController::class, 'adminList'])->name('dashboard');
    Route::post('/dashboard/store', [ReviewController::class, 'storeAdmin'])->name('review.admin.store');
    Route::put('/dashboard/update/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.delete');

    // Admin Testimonial Management
    Route::get('/admin/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimonials/update/{id}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');


    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
