<?php

use Illuminate\Support\Facades\Route;
// use App\Jobs\GenJob;
// use App\Jobs\TransactionJob;
//use App\Mail\GenMail;
use Carbon\Carbon;
use App\Http\Controllers\FlutterwaveController;

// use App\Monnify;
// use App\Account;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::get("/", "Admin\AdminController@index");

Route::get("/q", function () {

    return \geoip(request()->getClientIp())->getAttribute('city').', '.\geoip(request()->getClientIp())->getAttribute('country');

    return "OK, what are you looking for here Oga?";
    \Artisan::call("storage:link");
    return $_SERVER["DOCUMENT_ROOT"];
});
Route::get("make_perm", "AdminPermissionController@make_perm")->name("make_perm");

Route::group(["prefix" => "admin"], function () {
    Route::group(["prefix" => "auth"], function () {
        Route::post("/login", "Admin\AuthController@login")->name("admin.login");
        Route::get("/password/reset", "Admin\Auth\ForgotPasswordController@showLinkRequestForm")->name("admin.password.request");
        Route::post("/password/email", "Admin\Auth\ForgotPasswordController@sendResetLinkEmail")->name("admin.password.email");
        Route::post("/password/reset", "Admin\Auth\ResetPasswordController@reset");
        Route::get("/password/reset/{token}", "Admin\Auth\ResetPasswordController@showResetForm")->name("admin.password.reset");
        Route::get("/dashboard", "Admin\AdminController@dashboard")->name("dashboard");
    });

    Route::get("/", "Admin\AdminController@users");
    Route::get("/register", "Admin\AdminController@create");
    Route::get("/{id}", "Admin\AdminController@show")->where("id", "[0-9]+");
    Route::post("/signup", "Admin\AdminController@store")->name("signup");
    Route::get("/{id}/edit", "Admin\AdminController@edit")->where("id", "[0-9]+");
    Route::put("/{id}", "Admin\AdminController@update")->where("id", "[0-9]+");
    Route::delete("/{id}", "Admin\AdminController@delete")->where("id", "[0-9]+");
    Route::get("/dashboard", "Admin\AdminController@dashboard")->name("dashboard");
    Route::get("/settings", "Admin\AdminController@settings");
    Route::get("/banners", "Admin\ServiceCategoryController@allBanners");
    Route::get("/banner/{id}", "Admin\ServiceCategoryController@updateBanner");
    Route::get("/banner/{id}/delete", "Admin\ServiceCategoryController@deleteBanner")->name("banner.delete");
    Route::delete("/banner/{id}", "Admin\ServiceCategoryController@deleteBanner")->name("banner.delete");
    Route::post("/banners", "Admin\ServiceCategoryController@banners")->name("banner.create");
    Route::post("/new-setting", "Admin\AdminController@saveSettings")->name("setting.add");
    Route::post("/new-config", "Admin\SettingController@update")->name("config.add");

    /** Accounts route */
    Route::group(["prefix" => "account"], function () {
        Route::get("/", "Admin\PagesController@accountCategories");
        Route::get("/new-category", "Admin\Account\AccountCategoryController@create");
        Route::post("/category", "Admin\Account\AccountCategoryController@newCategory")->name("account.category");
        Route::get("category/{id}", "Admin\Account\AccountCategoryController@show")->where("id", "[0-9]+");
        Route::put("/category/{id}", "Admin\Account\AccountCategoryController@updateCategory")->where("id", "[0-9]+");
        Route::get("/{id}/delete", "Admin\Account\AccountCategoryController@delete")->where("id", "[0-9]+");
    });

    /** Investment route */
    Route::group(["prefix" => "investment"], function () {
        Route::get("/", "Admin\InvestmentController@index");
        Route::get("/{id}/invest", "Admin\InvestmentController@invest");
        Route::get("/new-investment", "Admin\InvestmentController@create");
        Route::post("/investment", "Admin\InvestmentController@store")->name("investment.add");
        Route::get("/{id}/investors", "Admin\InvestmentController@investors");
        Route::get("/{id}", "Admin\InvestmentController@show")->where("id", "[0-9]+");
        Route::get("/{id}/edit", "Admin\InvestmentController@edit")->where("id", "[0-9]+");
        Route::put("/{id}", "Admin\InvestmentController@update")->where("id", "[0-9]+");
        Route::get("/{id}/delete", "Admin\InvestmentController@delete")->where("id", "[0-9]+");
    });

    Route::group(["prefix" => 'debt-recovery'], function() {
        Route::get("/", "Admin\DebtRecoveryController@showRecoveryPage");
        Route::post("/profile", "Admin\DebtRecoveryController@getDebt")->name("debt.find");
        Route::get("/profile/{id}", "Admin\DebtRecoveryController@show")->name("debt.show");
        Route::post("/recover", "Admin\DebtRecoveryController@createPlan")->name("recovery.create");
        Route::get("/create", "Admin\DebtRecoveryController@debtRecovery");
    });

    /** Loan route */
    Route::group(["prefix" => "loan"], function () {
        Route::get("/", "Admin\LoanController@index");
        Route::get("/new-loan", "Admin\LoanController@create");
        Route::post("/loan", "Admin\LoanController@store")->name("loan.add");
        Route::get("/{id}/loanees", "Admin\LoanController@userLoans");
        Route::get("/{id}", "Admin\LoanController@show")->where("id", "[0-9]+");
        Route::get("/{id}/edit", "Admin\LoanController@edit")->where("id", "[0-9]+");
        Route::put("/{id}", "Admin\LoanController@update")->where("id", "[0-9]+");
        Route::get("/manage/{id}", "Admin\LoanController@manage")->name('loan.update');
        Route::get("/{id}/delete", "Admin\LoanController@delete")->where("id", "[0-9]+");

        /** User loan routes */
        Route::get("/users", "Admin\LoanController@userLoans");
        Route::put("/user/{id}", "Admin\LoanController@approve")->where("id", "[0-9]+");
    });

    /** Profile route */
    Route::group(["prefix" => "users"], function () {
        Route::get("/", "Admin\Profile\ProfileController@index");
        Route::get("/new-user", "Admin\Profile\ProfileController@create");
        Route::post("/register", "Admin\Profile\ProfileController@newProfile")->name("profile.register");
        Route::get("/{id}", "Admin\Profile\ProfileController@show")->where("id", "[0-9]+");
        Route::get("/{id}/edit", "Admin\Profile\ProfileController@edit")->where("id", "[0-9]+");
        Route::put("/{id}", "Admin\Profile\ProfileController@updateProfile")->where("id", "[0-9]+");
        Route::get("/{id}/delete", "Admin\Profile\ProfileController@delete")->where("id", "[0-9]+");
        Route::get("/agents", "Admin\Profile\ProfileController@agents");

        Route::group(['prefix' => 'account'], function () {
            Route::resource('/', 'User\AccountController');
            Route::post("/wallet-topup","User\AccountController@manageWallet")->name("user.account.manage");
        });

        Route::group(['prefix' => 'kyc'], function() {
            Route::get("/{id}", "Admin\Profile\ProfileController@updateKyc")->where("id", "[0-9]+")->name('kyc.update');
            Route::get("/social/{id}", "Admin\Profile\ProfileController@manageSocial")->where("id", "[0-9]+")->name('social.update');
            Route::get("/employment/{id}", "Admin\Profile\ProfileController@manageEmployment")->where("id", "[0-9]+")->name('employment.update');
            Route::get("/kin/{id}", "Admin\Profile\ProfileController@manageKin")->where("id", "[0-9]+")->name('kin.update');
        });

    });

    /** Transaction route */
    Route::group(["prefix" => "transactions"], function () {
        Route::get("/", "Admin\PagesController@transactions");
        Route::post("/webhook", "ShagoController@transConfirmation");

        /** Bank route */
        Route::group(["prefix" => "bank"], function () {
            Route::get("/", "Admin\BankController@index");
            Route::get("/add/{id}", "Admin\BankController@create")->where("id", "[0-9]+");
            Route::post("/new", "Admin\BankController@newBank")->name("bank.add");
            Route::get("/{id}", "Admin\BankController@show")->where("id", "[0-9]+");
            Route::get("/{id}/delete", "Admin\BankController@delete");
        });

        /** Card */
        Route::group(["prefix" => "card"], function () {
            Route::get("/add/{id}", "Admin\CardController@create")->where("id", "[0-9]+");
            Route::post("/new", "Admin\CardController@store")->name("card.add");
            Route::get("/{id}/delete", "Admin\CardController@delete");
        });
    });

    /** Services route */
    Route::group(["prefix" => "services"], function () {
        Route::get("/", "Admin\ServiceController@allServices");
        Route::get("/new-service", "Admin\ServiceController@create");
        Route::post("/add-service", "Admin\ServiceController@newServiceProvider")->name("service.add");
        Route::get("/{id}/edit", "Admin\ServiceController@edit")->where("id", "[0-9]+");
        Route::get("/{id}", "Admin\ServiceController@show")->where("id", "[0-9]+");
        Route::put("/{id}", "Admin\ServiceController@update")->where("id", "[0-9]+");
        Route::get("/{id}/delete", "Admin\ServiceController@delete")->where("id", "[0-9]+");

        /** Services category route */
        Route::group(["prefix" => "category"], function () {
            Route::get("/", "Admin\ServiceCategoryController@allServiceProviders");
            Route::get("/new-category", "Admin\ServiceCategoryController@create");
            Route::post("/add-provider", "Admin\ServiceCategoryController@newServiceProvider")->name("category.add");
            Route::get("/{id}/edit", "Admin\ServiceCategoryController@edit")->where("id", "[0-9]+");
            Route::get("/{id}", "Admin\ServiceCategoryController@show")->where("id", "[0-9]+");
            Route::put("/{id}", "Admin\ServiceCategoryController@update")->where("id", "[0-9]+");
            Route::get("/{id}/delete", "Admin\ServiceCategoryController@delete")->where("id", "[0-9]+");
        });
    });

    Route::group(['prefix' => 'support'], function () {
        Route::get('/', 'Admin\TicketController@index');
        Route::get('/tickets/{filter}', 'Admin\TicketController@ticketFilter')->where('filter', '[A-z]+');
        Route::get('/tickets/{id}', 'Admin\TicketController@show');
        Route::post('/tickets/reply', 'Admin\TicketController@reply')->name('reply.store');
        Route::get('/tickets/{id}/close', 'Admin\TicketController@close')->name('ticket.close');
        Route::get('/{id}/delete', 'Admin\TicketController@destroy')->name('ticket.delete');
    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact support@Mavunifs.com'], 404);
});


Route::any('flutterwave-page',[FlutterwaveController::class, 'index']);







































