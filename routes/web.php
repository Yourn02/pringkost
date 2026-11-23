<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KostController;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\SearcherMiddleware;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKostController;
use App\Http\Controllers\Admin\AdminFacilityController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\KostController as OwnerKostController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\Owner\NotificationController as OwnerNotificationController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\KostCatalogController as UserKostCatalogController;
use App\Http\Controllers\User\FavoriteController as UserFavoriteController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\NotificationController as UserNotificationController;
use Illuminate\Support\Facades\Auth;


// Rute Publik Utama
Route::get('/', [KostController::class, 'index'])->name('kost.index');
Route::get('/home', [KostController::class, 'index'])->name('home');
Route::get('/kost/{id}', [KostController::class, 'show'])->name('kost.show');

// Rute khusus Administrator
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kosts', [AdminKostController::class, 'index'])->name('kosts.index');
    Route::get('/kosts/{id}', [AdminKostController::class, 'show'])->name('kosts.show');
    Route::patch('/kost/{id}/approve', [AdminKostController::class, 'approve'])->name('kost.approve');
    Route::post('/kost/{id}/approve', [AdminKostController::class, 'approve'])->name('kost.approve.post');
    Route::patch('/kost/{id}/reject', [AdminKostController::class, 'reject'])->name('kost.reject');
    Route::post('/kost/{id}/reject', [AdminKostController::class, 'reject'])->name('kost.reject.post');

    Route::get('/facilities', [AdminFacilityController::class, 'index'])->name('facilities.index');
    Route::post('/facilities', [AdminFacilityController::class, 'store'])->name('facilities.store');
    Route::post('/facility', [AdminFacilityController::class, 'store'])->name('facility.store');
    Route::get('/facilities/{id}/edit', [AdminFacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/facilities/{id}', [AdminFacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{id}', [AdminFacilityController::class, 'destroy'])->name('facilities.destroy');
    Route::delete('/facility/{id}', [AdminFacilityController::class, 'destroy'])->name('facility.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{id}/status', [AdminUserController::class, 'updateStatus'])->name('users.status.update');
});

// Rute khusus untuk Pemilik (Mitra PringKost)
Route::middleware(['auth', OwnerMiddleware::class])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kosts', [OwnerKostController::class, 'index'])->name('kosts.index');
    Route::get('/kosts/create', [OwnerKostController::class, 'create'])->name('kosts.create');
    Route::post('/kosts', [OwnerKostController::class, 'store'])->name('kosts.store');
    Route::get('/kosts/{id}', [OwnerKostController::class, 'show'])->name('kosts.show');
    Route::get('/kosts/{id}/edit', [OwnerKostController::class, 'edit'])->name('kosts.edit');
    Route::put('/kosts/{id}', [OwnerKostController::class, 'update'])->name('kosts.update');
    Route::delete('/kosts/{id}', [OwnerKostController::class, 'destroy'])->name('kosts.destroy');
    Route::delete('/kost-images/{id}', [OwnerKostController::class, 'deleteImage'])->name('kosts.image.delete');

    Route::get('/kost/create', [OwnerKostController::class, 'create'])->name('kost.create');
    Route::post('/kost', [OwnerKostController::class, 'store'])->name('kost.store');
    Route::get('/kost', [OwnerKostController::class, 'index'])->name('kost.index');

    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [OwnerBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/update', [OwnerBookingController::class, 'updateStatus'])->name('bookings.update');

    Route::get('/profile', [OwnerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [OwnerProfileController::class, 'update'])->name('profile.update');

    Route::get('/notifications', [OwnerNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [OwnerNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [OwnerNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [OwnerNotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Rute khusus untuk Pengguna / Pencari Kost (Mahasiswa / Umum)
Route::middleware(['auth', SearcherMiddleware::class])->prefix('user')->name('user.')->group(function () {
    // 1. Dashboard Utama Pengguna
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // 2. Cari Kost (Katalog dengan Filter Lengkap)
    Route::get('/kosts', [UserKostCatalogController::class, 'index'])->name('kosts.index');
    Route::get('/kosts/{id}', [UserKostCatalogController::class, 'show'])->name('kosts.show');

    // 3. Kost Favorit (Wishlist)
    Route::get('/favorites', [UserFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{kostId}/toggle', [UserFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{kostId}', [UserFavoriteController::class, 'destroy'])->name('favorites.destroy');

    // 4. Pemesanan Saya (Bookings)
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [UserBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{kostId}', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::delete('/bookings/{id}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');

    // 5. Notifikasi
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');

    // 6. Profil Saya
    Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
});

// Rute Pengguna Terautentikasi Umum & Kompatibilitas Legacy
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pemilik') {
            return redirect()->route('owner.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/kost/{id}/book', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-bookings', function () {
        return redirect()->route('user.bookings.index');
    })->name('booking.index');
    Route::get('/wishlist', function () {
        return redirect()->route('user.favorites.index');
    })->name('wishlist.index');
    Route::post('/wishlist/{kostId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/owner/kosts/{kost}/images', [OwnerKostController::class, 'storeImages'])->name('owner.kosts.images.store');
    Route::delete('/owner/kost-images/{image}', [OwnerKostController::class, 'destroyImage'])->name('owner.kosts.images.destroy');
});

require __DIR__.'/auth.php';