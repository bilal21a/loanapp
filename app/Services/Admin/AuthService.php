<?php 
namespace App\Services\Admin;

use App\Repositories\Admin\AuthRepository;

class AuthService {

    protected $authRepo;

    public function __construct(AuthRepository $authRepo) {
        $this->authRepo = $authRepo;
    }

    public function login($request) {
        return $this->authRepo->login($request);
    }

    public function phoneChecker($request) {
        return $this->authRepo->phone_checker($request);
    }

    public function sendOTP($request) {
        return $this->authRepo->sendOTP($request);
    }

    public function verifyOTP($request) {
        return $this->authRepo->verify_OTP($request);
    }

    public function verifyBVN($request) {
        return $this->authRepo->verify_BVN($request);
    }

    public function confirmPassword($request) {
        return $this->authRepo->confirmPassword($request);
    }

    public function forgotPassword($request) {
        return $this->authRepo->forgotPassword($request);
    }

    public function resetPassword($request) {
        return $this->authRepo->resetPassword($request);
    }
    
}