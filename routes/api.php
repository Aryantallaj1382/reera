<?php

use App\Http\Controllers\Api\ads\AdsController;
use App\Http\Controllers\Api\ads\Business\BusinessController;
use App\Http\Controllers\Api\ads\Business\StoreBusinessController;
use App\Http\Controllers\Api\ads\Business\UpdateBusinessController;
use App\Http\Controllers\Api\ads\Digital\DigitalController;
use App\Http\Controllers\Api\ads\Digital\StoreDigitalController;
use App\Http\Controllers\Api\ads\Digital\UpdateDigitalController;
use App\Http\Controllers\Api\ads\Housemate\HousemateController;
use App\Http\Controllers\Api\ads\Housemate\StoreHousemateController;
use App\Http\Controllers\Api\ads\Housemate\UpdateHousemateController;
use App\Http\Controllers\Api\ads\Housing\HousingController;
use App\Http\Controllers\Api\ads\Housing\StoreHousingController;
use App\Http\Controllers\Api\ads\Housing\UpdateHousingController;
use App\Http\Controllers\Api\ads\Kitchen\KitchenController;
use App\Http\Controllers\Api\ads\Kitchen\StoreKitchenController;
use App\Http\Controllers\Api\ads\Kitchen\UpdateKitchenController;
use App\Http\Controllers\Api\ads\PersonalAds\PersonalAdController;
use App\Http\Controllers\Api\ads\PersonalAds\StorePersonalAdController;
use App\Http\Controllers\Api\ads\PersonalAds\UpdatePersonalController;
use App\Http\Controllers\Api\ads\Recruitment\RecruitmentController;
use App\Http\Controllers\Api\ads\Recruitment\StoreRecruitmentController;
use App\Http\Controllers\Api\ads\Recruitment\UpdateRecruitmentController;
use App\Http\Controllers\Api\ads\Service\ServiceController;
use App\Http\Controllers\Api\ads\Service\StoreServicesController;
use App\Http\Controllers\Api\ads\Service\UpdateServiceController;
use App\Http\Controllers\Api\ads\Ticket\StoreTicketController;
use App\Http\Controllers\Api\ads\Ticket\TicketController;
use App\Http\Controllers\Api\ads\Trip\StoreTripController;
use App\Http\Controllers\Api\ads\Trip\TripController;
use App\Http\Controllers\Api\ads\Trip\UpdateTripController;
use App\Http\Controllers\Api\ads\UserShowController;
use App\Http\Controllers\Api\ads\Vehicle\StoreVehicleController;
use App\Http\Controllers\Api\ads\Vehicle\UpdateVehicleController;
use App\Http\Controllers\Api\ads\Vehicle\VehicleController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\Profile\DashboardController;
use App\Http\Controllers\Api\Profile\LikedAdsController;
use App\Http\Controllers\Api\Profile\MyAdsController;
use App\Http\Controllers\Api\Profile\MyCommentController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\WalletController;
use Illuminate\Support\Facades\Route;




Route::prefix('user_show')->group(function () {
    Route::get('/{id}', [UserShowController::class, 'index']);          // ارسال کد تایید
    Route::get('/rate/{id}', [UserShowController::class, 'rate']); // بررسی وجود کاربر
    Route::get('/comment/{id}', [UserShowController::class, 'user_Comments']);              // ورود
    Route::get('/ads', [UserShowController::class, 'user_ads']);
});
Route::middleware('optional.auth')->group(function () {
Route::get('/getCountries', [CountryController::class, 'getCountries']);
Route::get('/getNationality', [CountryController::class, 'getNationality']);
Route::get('/getLanguage', [CountryController::class, 'getLanguage']);
Route::get('/info', [ProfileController::class, 'info']);
Route::get('/currency', [AdsController::class, 'currency']);
Route::get('/getCategory', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::get('/getAllCategory', [\App\Http\Controllers\Api\ads\Housing\StoreHousingController::class, 'index2']);
Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);          // ارسال کد تایید
    Route::post('/check-user-exists', [AuthController::class, 'checkUserExists']); // بررسی وجود کاربر
    Route::post('/login', [AuthController::class, 'loginOrRegister']);              // ورود
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); // خروج
    Route::post('reset', [AuthController::class, 'resetPasswordWithOtp']);
});


Route::prefix('ads')->controller(AdsController::class)->group(function () {
    Route::get('/',  'index');
    Route::get('/comments/{id}',  'comments');
    Route::get('/rates',  'rates');
    Route::post('/convert',  'convert1');
    Route::delete('/delete/{id}',  'delete');
    Route::post('/request/{id}',  'request_ad')->middleware('auth:sanctum');
    Route::get('/get_filters',  'get_filters');
    Route::post('/like/{id}',  'toggleLike')->middleware('auth:sanctum');
    Route::post('/{id}/report', 'store')->middleware('auth:sanctum');

    Route::prefix('housing')->controller(HousingController::class)->group(function () {
        Route::get('/ ', 'index');
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show');
    });
    Route::prefix('housemate')->controller(HousemateController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('digital')->controller(DigitalController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });

    Route::prefix('business')->controller(BusinessController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('kitchen')->controller(KitchenController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('personal')->controller(PersonalAdController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('service')->controller(ServiceController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('vehicle')->controller(VehicleController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });

    Route::prefix('ticket')->controller(TicketController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('trip')->controller(TripController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('visa')->controller(\App\Http\Controllers\Api\ads\Visa\VisaController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });
    Route::prefix('recruitment')->controller(RecruitmentController::class)->group(function () {
        Route::get('/currency', [AdsController::class, 'rates']);
        Route::get('/get_filters', 'get_filters')->middleware('optional.auth');

        Route::get('/resume', 'resume')->middleware('optional.auth');
        Route::get('/resume/pdf', 'downloadPdf')->middleware('optional.auth');
        Route::get('/{id}', 'show')->middleware('optional.auth');
    });


});
Route::post('/comment',  [\App\Http\Controllers\CommentController::class, 'store'])->middleware('auth:sanctum');
Route::prefix('profile/user/{id}')->controller(\App\Http\Controllers\Api\ads\UserProfileController::class)->group(function () {
    Route::get('/ads',  'ads');
    Route::get('/rate',  'rate');
    Route::get('/user_info',  'user_info');
    Route::get('/user_attributes',  'user_attributes');
    Route::get('/comments',  'comments')->middleware('optional.auth');
});
Route::prefix('update')->group(function () {
    Route::prefix('business')->controller(UpdateBusinessController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('digital')->controller(UpdateDigitalController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('housemate')->controller(UpdateHousemateController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('housing')->controller(UpdateHousingController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('kitchen')->controller(UpdateKitchenController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('ticket')->controller(\App\Http\Controllers\Api\ads\Ticket\UpdateTicketController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('personal')->controller(UpdatePersonalController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
    Route::prefix('recruitment')->controller(UpdateRecruitmentController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
        Route::post('/seventh/{id}',  'seventh');
    });
    Route::prefix('vehicles')->controller(UpdateVehicleController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
        Route::post('/seventh/{id}',  'seventh');
    });
    Route::prefix('trip')->controller(UpdateTripController::class)->group(function () {
        Route::post('/first',  'first');
    });
    Route::prefix('services')->controller(UpdateServiceController::class)->group(function () {
        Route::get('/{id}',  'get');
        Route::post('/first/{id}',  'first');
        Route::post('/second/{id}',  'second');
        Route::post('/third/{id}',  'third');
        Route::post('/fourth/{id}',  'fourth');
        Route::post('/fifth/{id}',  'fifth');
        Route::post('/sixth/{id}',  'sixth');
    });
});
Route::prefix('store')->middleware('auth:sanctum')->group(function () {
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
    Route::prefix('kitchen')->controller(StoreKitchenController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');

    });
    Route::prefix('personal')->controller(StorePersonalAdController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');
    });
    Route::prefix('services')->controller(StoreServicesController::class)->group(function () {
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
    Route::prefix('trip')->controller(StoreTripController::class)->group(function () {
        Route::post('/first',  'first');
        Route::post('/second',  'second');
    });

    Route::prefix('vehicle')->controller(StoreVehicleController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');
    });
    Route::prefix('recruitment')->controller(StoreRecruitmentController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::post('/seventh',  'seventh');
    });
    Route::prefix('visa')->controller(\App\Http\Controllers\Api\ads\Visa\StoreVisaController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
    });
    Route::prefix('ticket')->controller(StoreTicketController::class)->group(function () {
        Route::get('/',  'index');
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
        Route::delete('/delete/{id}',  'delete');
    });
    Route::prefix('business')->controller(StoreBusinessController::class)->group(function () {
        Route::post('/first',  'first');
        Route::post('/second',  'second');
        Route::post('/third',  'third');
        Route::post('/fourth',  'fourth');
        Route::post('/fifth',  'fifth');
        Route::post('/sixth',  'sixth');
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


        Route::get('/DigitalCurrency',  'DigitalCurrency');
        Route::post('/DigitalCurrency',  'storeDigitalCurrency');
        Route::delete('/DigitalCurrency/{id}',  'destroyDigitalCurrency');

        Route::prefix('request')->controller(\App\Http\Controllers\Api\Profile\ChatController::class)->group(function () {
            Route::get('/',  'myAds');
            Route::get('/adChats/{id}',  'adChats');
            Route::post('/accept/{id}',  'accept');
            Route::post('/rejected/{id}',  'rejected');
        });

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

    Route::prefix('profile')->group(function () {
        Route::get('/myAds',  [MyAdsController::class,'index']);
        Route::get('/dashboard',  [DashboardController::class,'index']);
        Route::get('/sold/{id}',  [DashboardController::class,'sold']);
        Route::get('/my_comment',  [MyCommentController::class,'myAdComments']);
        Route::post('/comment/like/{id}',  [MyCommentController::class,'toggleLike']);
        Route::get('/my_rate',  [MyCommentController::class,'myRate']);
        Route::get('/balance',  [WalletController::class,'balance']);
        Route::get('/transaction',  [WalletController::class,'index']);
    });
    Route::delete('/ad/delete/{id}',  [MyAdsController::class, '    delete']);
    Route::post('/ad/extension/{id}',  [MyAdsController::class, 'extension']);
    Route::post('/ad/sold/{id}',  [MyAdsController::class, 'sold']);

    Route::prefix('profile/ticket')->controller(\App\Http\Controllers\Api\Profile\TicketController::class)->group(function () {
        Route::get('/',  'userTickets');
        Route::get('/{id}',  'show');
        Route::post('/store',  'store');
        Route::post('/addMessage/{id}',  'addMessage');

    });

});
});
