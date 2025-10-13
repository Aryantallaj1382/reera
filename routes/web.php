<?php

use App\Http\Controllers\admin\AdminAdsController;
use App\Http\Controllers\admin\AdminCategory;
use App\Http\Controllers\admin\AdminCommentController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\digital\mobile\DigitalMobileController;
use App\Http\Controllers\admin\kitchen\KitchenController;
use App\Http\Controllers\admin\personal\PersonalController;
use App\Http\Controllers\admin\recuitment\recuitmentController;
use App\Http\Controllers\admin\service\ServiceController;
use App\Http\Controllers\admin\ticket\TicketController;
use App\Http\Controllers\admin\vehicle\VehicleController;

use Illuminate\Support\Facades\Route;

Route::get('/', function (){
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
/////////////////////////////////////////////////////////////////////////////////////////////////////////



Route::prefix('digital')->controller(DigitalMobileController::class)->name('digital.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});


Route::prefix('vehicle')->controller(VehicleController::class)->name('vehicle.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});

Route::prefix('kitchen')->controller(KitchenController::class)->name('kitchen.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});
Route::prefix('service')->controller(ServiceController::class)->name('service.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});
Route::prefix('personal')->controller(PersonalController::class)->name('personal.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});
Route::prefix('ticket')->controller(TicketController::class)->name('ticket.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});
Route::prefix('recuitment')->controller(RecuitmentController::class)->name('recuitment.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::patch('/ads/{id}/status', 'updateStatus')->name('ads.updateStatus');
    Route::delete('/{id}', 'destroy')->name('destroy');
});







