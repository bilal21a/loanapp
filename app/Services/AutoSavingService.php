<?php
namespace App\Services;

use App\Repositories\AutoSavingRepository;

class AutoSavingService {

    protected $autosaveRepo;

    public function __construct(AutoSavingRepository $autosaveRepo) {
        $this->autosaveRepo = $autosaveRepo;
    }

    public function index() {
        return $this->autosaveRepo->index();
    }

    public function store($request) {
        return $request->all();
        return $this->autosaveRepo->create($request);
    }

    public function find($id) {
        return $this->autosaveRepo->find($id);
    }

    public function update($id, $request) {
        return $this->autosaveRepo->update($id, $request);
    }

    public function delete($id) {
        return $this->autosaveRepo->delete($id);
    }

    public function toggleStatus($id) {
        return $this->autosaveRepo->acivateOrDeacticate($id);
    }
}
