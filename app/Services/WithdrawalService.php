<?php 
namespace App\Services;

use App\Repositories\WithdrawalRepository;

class WithdrawalService {

    protected $withdrawalRepo;

    public function __construct(WithdrawalRepository $withdrawalRepo) {
        $this->withdrawalRepo = $withdrawalRepo;
    }

    public function index() {
        return $this->withdrawalservice->index();
    }

    public function store($request) {
        return $this->withdrawalRepo->store($request);
    }

    public function find($id) {
        return $this->withdrawalRepo->find($id);
    }

    public function update($id, $request) {
        return $this->withdrawalRepo->update($id, $request->all());
    }
}