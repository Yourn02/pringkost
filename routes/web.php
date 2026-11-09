<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WishlistController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Publik Utama (Halaman Depan & Pencarian)
Route::get('/', [KostController::class, 'index'])->name('kost.index');
Route::get('/home', [KostController::class, 'index'])->name('home');
// Rute Detail Kost Publik
Route::get('/kost/{id}', [KostController::class, 'show'])->name('kost.show');

// Rute khusus Administrator
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/kost/{id}/approve', [AdminController::class, 'approve'])->name('kost.approve');
    Route::patch('/kost/{id}/reject', [AdminController::class, 'reject'])->name('kost.reject');
    
    Route::post('/facility', [AdminController::class, 'storeFacility'])->name('facility.store');
    Route::delete('/facility/{id}', [AdminController::class, 'destroyFacility'])->name('facility.destroy');
});

// Rute khusus untuk Pemilik (Mitra) yang sudah login & berstatus owner
Route::middleware(['auth', OwnerMiddleware::class])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/kost/create', [OwnerController::class, 'create'])->name('kost.create');
    Route::post('/kost', [OwnerController::class, 'store'])->name('kost.store');
    Route::resource('kost', OwnerController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/kost/{id}/book', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/owner/bookings', [BookingController::class, 'ownerIndex'])->name('owner.bookings');
    Route::post('/owner/bookings/{id}/update', [BookingController::class, 'updateStatus'])->name('owner.bookings.update');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{kostId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/kost/{id}/approve', [AdminController::class, 'approve'])->name('admin.kost.approve');
    Route::post('/admin/kost/{id}/reject', [AdminController::class, 'reject'])->name('admin.kost.reject');
    Route::post('/admin/facilities', [AdminController::class, 'storeFacility'])->name('admin.facilities.store');
    Route::delete('/admin/facilities/{id}', [AdminController::class, 'destroyFacility'])->name('admin.facilities.destroy');
    Route::get('/dashboard', [App\Http\Controllers\KostController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';