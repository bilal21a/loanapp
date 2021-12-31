<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\LoginRequest;
use App\Services\AuthService;
use App\Services\BankService;
use Hash;
use App\Profile;

class AuthController extends Controller
{

    protected $authservice;
    protected $bankservice;

    public function __construct(AuthService $authservice, BankService $bankservice) {
        $this->authservice = $authservice;
        $this->bankservice = $bankservice;
    }
    
    public function login(LoginRequest $request) {
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

    public function changePassword(Request $request) {
       return $this->authservice->changetPassword($request);
    }

}
