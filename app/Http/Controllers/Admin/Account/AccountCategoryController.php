<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AccountCategoryRequest;
use App\Services\Admin\AccountCategoryService;
USE App\AccountCategory;

class AccountCategoryController extends Controller
{
    protected $accountCatService;

    public function __construct(AccountCategoryService $accountCatService) {
        $this->accountCatService = $accountCatService;
        $this->middleware(["assign.guard", "auth:admin"]);
    }

    public function index() {
         return $this->accountCatService->categories();
    }

    public function create() {
         return $this->accountCatService->create();
    }

    public function newCategory(AccountCategoryRequest $request) {      
        return $this->accountCatService->createCategory($request);
    }

    public function show($id) {
        return $this->accountCatService->findCategory($id);
    }

    public function updateCategory($id, AccountCategoryRequest $request) {
        return $this->accountCatService->update($id, $request);
    }

    public function delete($id) {
        return $this->accountCatService->delete($id);
    }
}
