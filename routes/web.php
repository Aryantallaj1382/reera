<?php

use App\Http\Controllers\admin\AdApprovalController;
use App\Http\Controllers\admin\AdminAdController;
use App\Http\Controllers\admin\AdminAdsController;
use App\Http\Controllers\admin\AdminCategory;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminCityController;
use App\Http\Controllers\admin\AdminCommentController;
use App\Http\Controllers\admin\AdminCountryController;
use App\Http\Controllers\admin\AdminTicketController;
use App\Http\Controllers\admin\ads\AdsController;
use App\Http\Controllers\admin\business\BusinessController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\digital\mobile\DigitalMobileController;
use App\Http\Controllers\admin\housemate\housemateController;
use App\Http\Controllers\admin\info\DigigtlBrandController;
use App\Http\Controllers\admin\info\VehicleBrandController;
use App\Http\Controllers\admin\kitchen\KitchenController;
use App\Http\Controllers\admin\personal\PersonalController;
use App\Http\Controllers\admin\real_astate\RealEstateController;
use App\Http\Controllers\admin\recuitment\recuitmentController;
use App\Http\Controllers\admin\service\ServiceController;
use App\Http\Controllers\admin\ticket\TicketController;
use App\Http\Controllers\admin\user\UserController;
use App\Http\Controllers\admin\vehicle\VehicleController;

use App\Http\Controllers\Api\ads\Recruitment\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return view('welcome');
});

Route::get('/mt', function () {
    Artisan::Call('migrate', ['--force' => true]);

    dd(Artisan::output());
});
Route::get('/resume/pdf', [RecruitmentController::class, 'downloadPdf'])->name('resume.pdf');

Route::get('/optimize', function () {
    Artisan::call('optimize');
    dd(Artisan::output());
});
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');



Route::get('/admin/ads/pending', [AdApprovalController::class, 'index'])->name('admin.ads.pending');
Route::get('/admin/ads/{ad}', [AdminAdController::class, 'show'])->name('admin.ads.show');
Route::post('/ads/{ad}/approve', [AdApprovalController::class, 'approve'])->name('admin.ads.approve');
Route::post('/ads/{ad}/reject', [AdApprovalController::class, 'reject'])->name('admin.ads.reject');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
});
Route::resource('countries', AdminCountryController::class);

Route::get('countries/{country}/cities', [AdminCityController::class, 'show'])->name('cities.show');
Route::post('countries/{country}/cities', [AdminCityController::class, 'store'])->name('cities.store');
Route::put('cities/{city}', [AdminCityController::class, 'update'])->name('cities.update');
Route::delete('cities/{city}', [AdminCityController::class, 'destroy'])->name('cities.destroy');
Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
Route::get('categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');

Route::post('categories/{category}/children', [AdminCategoryController::class, 'storeChild'])
    ->name('categories.children.store');

Route::put('categories/{category}', [AdminCategoryController::class, 'update'])
    ->name('categories.update');

Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])
    ->name('categories.destroy');
Route::get('admin/info', [\App\Http\Controllers\admin\InfoController::class, 'index'])
    ->name('info');
Route::resource('DigitalBrands', DigigtlBrandController::class);
Route::prefix('DigitalBrands/{brand}')->group(function () {
    Route::get('models', [DigigtlBrandController::class, 'models'])->name('DigitalBrands.models.index');
    Route::post('models', [DigigtlBrandController::class, 'store_model'])->name('DigitalBrands.models.store');
    Route::delete('models/{model}', [DigigtlBrandController::class, 'destroy_model'])->name('DigitalBrands.models.destroy');
});
Route::resource('KitchenBrand', \App\Http\Controllers\admin\info\KitchenBrandController::class);
Route::resource('VehicleBrand', \App\Http\Controllers\admin\info\VehicleBrandController::class);
Route::prefix('VehicleBrand/{brand}')->group(function () {
    Route::get('models', [VehicleBrandController::class, 'models'])->name('VehicleBrand.models.index');
    Route::post('models', [VehicleBrandController::class, 'store_model'])->name('VehicleBrand.models.store');
    Route::delete('models/{model}', [VehicleBrandController::class, 'destroy_model'])->name('VehicleBrand.models.destroy');
});
