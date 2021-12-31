<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AuthService;
use App\Services\Admin\BankService;
use App\Admin;

class AuthController extends Controller
{

    protected $authservice;
    protected $bankservice;

    public function __construct(AuthService $authservice, BankService $bankservice) {
        $this->authservice = $authservice;
        $this->bankservice = $bankservice;
    }
    
    public function login(Request $request) {
        return $this->authservice->login($request);
    }

    public function phoneChecker(Request $request) {
        return $this->authservice->phoneChecker($request);
    }

    public function requestOTP(Request $request) {
        return $this->authservice->sendOTP($request);
    }

    public function verifyOTP(Request $request) {
        return $this->authservice->verifyOTP($request);
    }

    public function verifyBVN(Request $request) {
        return $this->bankservice->verifyBVN($request);
    }

    public function sendPasswordResetLink(Request $request) {
        return $this->sendResetLinkEmail($request);
    }

    public function confirmPassword(Request $request) {
        return $this->authservice->confirmPassword($request);
    }

    public function forgotPassword(Request $request) {
        return $this->authservice->forgotPassword($request);
    }

    protected function resetPassword(Request $request) {
       return $this->authservice->resetPassword($request);
    }

}
