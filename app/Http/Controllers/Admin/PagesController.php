<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepository;
use App\Services\Admin\AccountCategoryService;
use App\AccountCategory;
use App\Savings;

class PagesController extends Controller
{
    protected $profile;
    protected $accountcategoryservice;

    public function __construct(ProfileRepository $profile, AccountCategoryService $accountcategoryservice) {
        $this->profile = $profile;
        $this->accountcategoryservice = $accountcategoryservice;
        $this->middleware(["assign.guard", "auth:admin"]);
    }


    public function account($id) {
        $data = $this->profile->getProfile($id);
        return view("website.admin.users.profile")->withUser($data);
    }

    public function transactions() {
        $data = Savings::with("user")->orderBy("created_at", 'desc')->get();
        return view("website.admin.transactions")->withTransactions($data);
    }

    public function accountCategories() {
         $data = $this->accountcategoryservice->categories();
        return view("website.admin.account.account-categories")->withCategories($data);
    }

    public function newAccountCatgeory() {
        $data = AccountCategory::orderBy("type", "asc")->get();
        return view("website.admin.account.new-category")->withCategories($data);
    }



}
