<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/** Routes */
Route::group(["prefix" => "v1"], function () {
    Route::get("/", function () {
        return "Mavunifs API version 1.0.0";
    });
    Route::post("/login", "User\AuthController@login");

    //Route::post("/checker", "User\AuthController@phoneChecker")->name('user.check');
    Route::group(["prefix" => "auth"], function () {
        Route::post("/checker", "User\AuthController@phoneChecker")->name('user.check');
        Route::post("/otp", "User\AuthController@requestOTP")->name('otp.get');
        Route::post("/verify", "User\AuthController@verifyOTP")->name('user.verify');
        Route::post("/verify-bvn", "User\AuthController@verifyBVN")->name('bvn.verify');
        Route::post("/confirm-password", "User\AuthController@confirmPassword")->middleware(["assign.guard:profile", "jwt.auth"]);
        Route::post("/change-password", "User\AuthController@changePassword")->middleware(["assign.guard:profile", "jwt.auth"]);
        Route::post("/forgot-password", "User\AuthController@forgotPassword");//->middleware(["assign.guard:profile", "jwt.auth"]);
        Route::post("/reset-password", "User\AuthController@resetPassword");
    });

    Route::group(["prefix" => "account"], function () {
        Route::post("/create", "User\ProfileController@newProfile");
        Route::get("/{id}", "User\ProfileController@show")->where('id', '[0-9]+');
        Route::put("/{id}", "User\ProfileController@updateProfile")->where('id', '[0-9]+');
        Route::delete("/{id}", "User\ProfileController@delete")->where('id', '[0-9]+');
        Route::post("/kyc", "User\KycController@update");
        Route::post("/next-of-kin", "User\ProfileController@nextOfKin");
        Route::post("/socialhandles", "User\KycController@socialHandle");
        Route::post("/employment-history", "User\KycController@employmentHistory");
        //Route::get("/settings", "User\ProfileController@userSettings");
    });

    Route::group(["prefix" => "transaction"], function () {

      /** Transaction routes */
        Route::get("/", "User\TransactionsController@index");
        Route::get("/{id}", "User\TransactionsController@show")->where('id', '[0-9]+');
        Route::get("/type/{type}", "User\TransactionsController@getByType")->where('type', '[a-z]+');
        Route::get("/user/{id}", "User\TransactionsController@getByUser")->where('id', '[0-9]+');
        Route::get("/vendor/{name}", "User\TransactionsController@getByVendor")->where('id', '[0-9]+');
        Route::get("/subtype/{name}", "User\TransactionsController@getBySubType")->where('id', '[0-9]+');

        Route::post("/wallet-topup", "Operations\AccountController@walletTopup");
        Route::post("/wallet-withdrawal", "Operations\AccountController@withdrawToWallet");
        Route::post("/withdraw", "Operations\AccountController@withdraw");

        /** Bank routes */
        Route::post("/bank/add", "User\BankController@newBank");
        Route::get("/bank/{id}", "User\BankController@show")->where('id', '[0-9]+');
        Route::put("/bank/{id}", "User\BankController@update")->where('id', '[0-9]+');
        Route::delete("/bank/{id}", "User\BankController@delete")->where('id', '[0-9]+');
        Route::get("/banks", "Payment\PaymentController@banks");

        /** Cards routes */
        Route::get("/cards", "User\CardController@index");
        Route::post("/card/add", "Operations\AccountController@addCard");
        Route::get("/card/{id}", "User\CardController@show")->where('id', '[0-9]+');
        Route::put("/card/{id}", "User\CardController@update")->where('id', '[0-9]+');
        Route::get("card/set-default/{id}", "User\CardController@setCard");
        Route::delete("/card/{id}", "User\CardController@delete")->where('id', '[0-9]+');

        /** Savings route */
        Route::post("/quick-save", "Operations\AccountController@quickSave");
        Route::post("/withdraw", "Operations\AccountController@withdraw");
        Route::get("/monnify", "MonnifyController@getToken");

        /** Investment */
        Route::get("/investments", "User\InvestmentController@index");
        Route::post("/invest", "User\InvestmentController@store");

        /** Loan route */
        Route::get("/user-loans", "User\LoanController@userLoans");
        Route::post("/loan", "User\LoanController@store");
        Route::get("/loan/{id}", "User\LoanController@show")->where("id", "[0-9]+");
        Route::post("/loan/pay", "User\LoanController@repayment");
        Route::delete("/loan/cancel/{id}", "User\LoanController@cancelRequest")->where("id", "[0-9]+");
        Route::delete("/loan/{id}", "User\LoanController@delete")->where("id", "[0-9]+");

        /** Settings */
        Route::group(["prefix" => "settings"], function () {
            Route::get("/", "User\SettingsController@settings");
            Route::post("/app-settings", "User\SettingsController@notificationSettings");
            Route::get("/app-settings", "User\SettingsController@getSettings");
            Route::post("/withdrawal", "Operations\WithdrawalController@store");
            Route::get("/withdrawal/{id}", "Operations\WithdrawalController@find")->where('id', '[0-9]+');
            Route::put("/withdrawal/{id}", "Operations\WithdrawalController@update")->where('id', '[0-9]+');
            Route::post("/autosave", "User\AutoSavingController@store");
            Route::get("/autosave/{id}", "User\AutoSavingController@show")->where('id', '[0-9]+');
            Route::put("/autosave/{id}", "User\AutoSavingController@update")->where('id', '[0-9]+');
        });
    });

    Route::group(["prefix" => "services"], function () {
        /**Shago */
        Route::get("/", "ServicesController@index");
        Route::get("/banners", "ServicesController@getBanners");
        Route::get("/categories/{type}", "ServicesController@getCategoryByType");
        Route::get("/categories", "ServicesController@categories");
        Route::get("/banners", "ServicesController@getBanners");
        Route::get("/categories/data", "ServicesController@data");
        Route::get("/categories/airtime", "ServicesController@airtime");
        Route::get("/categories/cable-tv", "ServicesController@cabletv");
        Route::get("/data-plan/{vendor}", "ServicesController@getDataPlans");
        Route::post("/verify-account", "Payment\PaymentController@verifyAccount");
        Route::post("/fund-transfer", "Payment\PaymentController@bankTransfer");
        Route::get("/loans", "ServicesController@loans");
    });

    Route::group(["prefix" => "bills"], function () {
        /**Shago */
        Route::post("/verify", "ShagoController@verify");
        Route::post("/buy-airtime", "ShagoController@buyAirtime");
        Route::post("/buy-data", "ShagoController@buyData");
        Route::post("/sme-data", "MobileMoneyController@cheapData");
        Route::post("/buy-cable", "ShagoController@cableSubscription");
        Route::post("/buy-electricity", "ShagoController@powerSubscription");
        Route::post("/buy-databundle", "ShagoController@databundlePurchase");

        /** Mobile money */
        // Route::post("/buy-airtime", "MobileMoneyController@buyAirtime");
        // Route::post("/buy-data", "MobileMoneyController@buyData");
        // Route::post("/buy-cable", "MobileMoneyController@cableSubscription");
        // Route::post("/buy-electricity", "MobileMoneyController@powerSubscription");
        // Route::post("/buy-pin", "MobileMoneyController@pinPurchase");
        // Route::get("/list/{vendor}", "MobileMoneyController@productList");
    });

    Route::group(['prefix' => 'support'], function () {
        Route::get('/{id}', 'User\TicketsController@index');
        Route::post('/ticket', 'User\TicketsController@store');
        Route::post('/ticket/reply', 'User\TicketsController@update');
        Route::get('/tickets/{filter}/filter', 'User\TicketsController@ticketFilter');
        Route::get('/tickets/{id}', 'User\TicketsController@show');
    });

    Route::group(["prefix" => "referer"], function() {
        Route::get("/", "User\RefererController@index");
        Route::get("/manage/{id}", "User\RefererController@manage");
        Route::post("/", "User\RefererController@store");
    });

    Route::get('/get-location/{longitude}/{latitude}', 'User\SettingsController@getLocation');

    Route::post('/payment_webhook_Mavunifs_2020', 'Payment\PaymentController@webhook');
    Route::post('/payment_webhook_paystack', 'Payment\PaymentController@webhookPaystack');
    Route::post('/payment_webhook_Mavunifs_2020_shago', 'Payment\PaymentController@shagoWebhook');
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact support@Mavunifs.com',
        "code" => 404,
    ], 404);
});
