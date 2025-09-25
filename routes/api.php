<?php

use App\Http\Controllers\Api\ads\AdsController;
use App\Http\Controllers\Api\ads\Digital\StoreDigitalController;
use App\Http\Controllers\Api\ads\Housemate\StoreHousemateController;
use App\Http\Controllers\Api\ads\Housing\HousingController;
use App\Http\Controllers\Api\ads\Housing\StoreHousingController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\Profile\LikedAdsController;
use App\Http\Controllers\Api\Profile\MyAdsController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\TicketController;



Route::get('/getCountries', [CountryController::class, 'getCountries']);
Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);          // ارسال کد تایید
    Route::post('/check-user-exists', [AuthController::class, 'checkUserExists']); // بررسی وجود کاربر
    Route::post('/login', [AuthController::class, 'loginOrRegister']);              // ورود
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // خروج
    Route::post('reset', [AuthController::class, 'resetPasswordWithOtp']);


});
Route::prefix('ads')->controller(AdsController::class)->group(function () {
    Route::get('/',  'index');
    Route::post('/like/{id}',  'toggleLike')->middleware('auth:sanctum');
    Route::prefix('house')->controller(HousingController::class)->group(function () {
        Route::get('/{slug}', 'show');
        Route::get('/ ', 'index');
        Route::get('/get_filters', 'get_filters');
        Route::get('/currency', [AdsController::class, 'rates']);

    });

});
Route::prefix('store')->group(function () {

    Route::prefix('housing')->controller(StoreHousingController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');

    });

    Route::prefix('housemate')->controller(StoreHousemateController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');
    });

    Route::prefix('digital')->controller(StoreDigitalController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');
    });

});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/',  'profile');
        Route::get('/attributes',  'getUserAttributes');
        Route::post('/update',  'updateProfile');
        Route::post('/update/attributes',  'updateUserAttributes');
        Route::get('/finances',  'finance');
        Route::post('/finances',  'storeFinance');
        Route::put('/finances/{id}',  'updateFinance');
        Route::delete('/finances/{id}',  'destroyFinance');


        Route::get('/video',  'showIntroVideo');
        Route::post('/video',  'storeIntroVideo');


        Route::get('/getLanguages',  'getLanguages');
        Route::post('/updateLanguages',  'updateLanguages');

        Route::get('/getResidencyStatus',  'getResidencyStatus');
        Route::post('/updateResidencyStatus',  'updateResidencyStatus');


        Route::get('/getSalaryRange',  'getSalaryRange');
        Route::post('/updateSalaryRange',  'updateSalaryRange');


        Route::get('/show_resume_file',  'show_resume_file');
        Route::post('/store_resume_file',  'store_resume_file');

        Route::get('/getWorkExperiences',  'getWorkExperiences');
        Route::post('/updateWorkExperiences',  'updateWorkExperiences');


        Route::get('/getEducations',  'getEducations');
        Route::post('/updateEducations',  'updateEducations');

        Route::get('/getSkills',  'getSkills');
        Route::post('/updateSkills',  'updateSkills');
    });

    Route::prefix('profile/like')->controller(LikedAdsController::class)->group(function () {
        Route::get('/',  'index');
    });

    Route::prefix('profile/myAds')->controller(MyAdsController::class)->group(function () {
        Route::get('/',  'index');

    });
    Route::prefix('profile/ticket')->controller(TicketController::class)->group(function () {
        Route::get('/',  'userTickets');
        Route::get('/{id}',  'show');
        Route::post('/store',  'store');
        Route::post('/addMessage/{id}',  'addMessage');

    });

});
