<?php

use App\Http\Controllers\admin\AdminAdsController;
use App\Http\Controllers\admin\AdminCategory;
use App\Http\Controllers\admin\AdminCommentController;
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mt', function () {
    Artisan::Call('migrate', ['--force' => true]);

    dd(Artisan::output());
});

Route::get('/optimize', function () {
    Artisan::call('optimize');
    dd(Artisan::output());
});
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

Route::prefix('ads')->controller(AdminAdsController::class)->name('ads.')->group(function () {
    Route::get('/','index')->name('index');
    Route::get('/show/{id}','show')->name('show');

});


Route::prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [AdminCommentController::class, 'comment'])->name('index');
    Route::patch('{comment}/approve', [AdminCommentController::class, 'approve'])->name('approve');
    Route::patch('{comment}/reject', [AdminCommentController::class, 'reject'])->name('reject');
    Route::delete('{comment}', [AdminCommentController::class, 'destroy'])->name('destroy');
});

// دسته‌بندی‌ها
Route::get('/category', [AdminCategory::class, 'category'])->name('category');
