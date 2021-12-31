<?php 
namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService {
    
    protected $profileRepo;

    public function __construct(ProfileRepository $profileRepo) {
        $this->profileRepo = $profileRepo;
    }

    public function index() {
        return $this->profileRepo->index();
    }

    public function create($request) {
        return $this->profileRepo->create($request);
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

    public function nextOfKin($request) {
        return $this->profileRepo->nextOfKin($request);
    }
}