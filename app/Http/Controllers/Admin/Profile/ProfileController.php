<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Services\Admin\ProfileService;

class ProfileController extends Controller
{
    protected $profileservice;

    public function __construct(ProfileService $profileservice) {
        $this->profileservice = $profileservice;
        $this->middleware(["assign.guard", "auth:admin"]);
    }

    public function index() {
        return $this->profileservice->index();
    }

    public function agents() {
        return $this->profileservice->agents();
    }

    public function create(Request $request) {
        return $this->profileservice->create($request);
    }

    public function newProfile(ProfileRequest $request) {
        return $this->profileservice->store($request->validated());
    }


    public function edit($id) {
        return $this->profileservice->edit($id);
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

    public function manageSocial($id) {
        return $this->profileservice->manageSocial($id);
    }

    public function manageEmployment($id) {
        return $this->profileservice->manageEmployment($id);
    }

    public function manageKin($id) {
        return $this->profileservice->manageKin($id);
    }

    public function updateKyc($id) {
        return $this->profileservice->updateKyc($id);
    }
}
