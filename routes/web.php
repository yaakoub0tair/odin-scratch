<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('links', \App\Http\Controllers\LinkController::class);
    
    Route::post('links/{link}/favorite', [\App\Http\Controllers\LinkController::class, 'favorite'])->name('links.favorite');
    Route::post('links/{link}/share', [\App\Http\Controllers\LinkController::class, 'share'])->name('links.share');
    Route::get('links/favorites', [\App\Http\Controllers\LinkController::class, 'favorites'])->name('links.favorites');
    
    // Soft delete routes
    Route::post('links/{link}/restore', [\App\Http\Controllers\LinkController::class, 'restore'])->name('links.restore');
    Route::delete('links/{link}/force-delete', [\App\Http\Controllers\LinkController::class, 'forceDelete'])->name('links.forceDelete');
    Route::get('links/trashed', [\App\Http\Controllers\LinkController::class, 'trashed'])->name('links.trashed');
    
    // Notification routes
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});

require __DIR__.'/auth.php';
