<?php

namespace App\Http\Controllers\Operations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\CardRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected $accountservice;

    public function __construct(AccountService $accountservice) {
        $this->accountservice = $accountservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
        return $this->accountservice->accounts();
    }

    public function store(Request $request) {
        return $this->accountservice->store($request);
    }

    public function show($id) {
        return $this->accountservice->findAccount($id);
    }

    public function updateAccount($id, Request $request) {
        return $this->accountservice->update($id, $request);
    }

    public function delete($id) {
        return $this->accountservice->delete($id);
    }

    public function deactivate($id) {
        return $this->accountservice->deactivateAccount($id);
    }

    public function quickSave(Request $request) {
        return $this->accountservice->quick_save($request);
    }

    public function withdraw(Request $request) {
        return $this->accountservice->withdrawal($request);
    }

    public function autosaveSetting(Request $request) {
        return $this->accountservice->settings($request);
    }

    public function autoSave(Request $request) {
        return $this->accountservice->auto_save($request);
    }

    public function addCard(CardRequest $request) {
        return $this->accountservice->addCard($request);
    }

    public function withdrawToWallet(Request $request) {

        return $this->accountservice->withdrawToWallet($request);

    }

    public function walletTopup(Request $request) {

        return $this->accountservice->walletTopup($request);

    }




}
