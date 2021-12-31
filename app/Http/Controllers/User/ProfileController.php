<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\ProfileRequest;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileservice;

    public function __construct(ProfileService $profileservice) {
        $this->profileservice = $profileservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"])->except("newProfile");
    }

    public function index() {
        return $this->profileservice->index();
    }

    public function newProfile(ProfileRequest $request) {
        return $this->profileservice->create($request);
    }
 
    public function show($id) {
        return $this->profileservice->getProfile($id);
    }

    public function updateProfile($id, Request $request) {
        return $this->profileservice->update($id, $request);
    }

    public function delete($id) {
        return $this->profileservice->delete($id);
    }

    public function nextOfKin(Request $request) {
        return $this->profileservice->nextOfKin($request);
    }
}
