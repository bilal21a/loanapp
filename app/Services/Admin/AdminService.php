<?php 
namespace App\Services\Admin;
use App\Repositories\Admin\AdminRepository;

class AdminService {
    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo) {
        $this->adminRepo = $adminRepo;
    }

    public function index() {
        return $this->adminRepo->index();
    }

    public function users() {
        return $this->adminRepo->users();
    }

    public function store($request) {
        return $this->adminRepo->store($request);
    }

    public function find($id) {
        return $this->adminRepo->find($id);
    }

    public function edit($id) {
        return $this->adminRepo->edit($id);
    }

    public function update($id, $request) {
        return $this->adminRepo->update($id, $request->all());
    }

    public function delete($id) {
        return $this->adminRepo->delete($id);
    }

    public function dashboard() {
        return $this->adminRepo->dashboard();
    }

    public function settings() {
        return $this->adminRepo->settings();
    }

    public function saveSettings($request) {
        return $this->adminRepo->saveSettings($request);
    }
}