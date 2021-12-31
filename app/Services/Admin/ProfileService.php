<?php
namespace App\Services\Admin;

use App\Repositories\Admin\ProfileRepository;

class ProfileService {

    protected $profileRepo;

    public function __construct(ProfileRepository $profileRepo) {
        $this->profileRepo = $profileRepo;
    }

    public function index() {
        return $this->profileRepo->index();
    }

    public function agents() {
        return $this->profileRepo->agents();
    }

    public function create($request) {
        return $this->profileRepo->create($request);
    }

    public function store($request) {
        return $this->profileRepo->store($request);
    }

    public function edit($id) {
        return $this->profileRepo->edit($id);
    }

    public function getProfile($id) {
        return $this->profileRepo->getProfile($id);
    }

    public function update($id, $request) {
        return $this->profileRepo->update($id, $request->all());
    }

    public function delete($id) {
        return $this->profileRepo->delete($id);
    }

    public function manageEmployment($id) {
        return $this->profileRepo->manageEmployment($id);
    }

    public function manageKin($id) {
        return $this->profileRepo->manageKin($id);
    }

    public function manageSocial($id) {
        return $this->profileRepo->manageSocial($id);
    }

    public function updateKyc($id) {
        return $this->profileRepo->updateKyc($id);
    }
}
