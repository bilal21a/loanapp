<?php 
namespace App\Services\Admin;

use App\Repositories\Admin\BankRepository;

class BankService {
     
    protected $bankRepo;

    public function __construct(BankRepository $bankRepo) {
        $this->bankRepo = $bankRepo;
    }

    public function index() {
        return $this->bankRepo->index();
    }

    public function create($id) {
        return $this->bankRepo->create($id);
    }

    public function newBank($request) {
        return $this->bankRepo->addBank($request);
    }

    public function find($id) {
        return $this->bankRepo->getBank($id);
    }

    public function update($id, $request) {
        return $this->bankRepo->update($id, $request->all());
    }

    public function delete($id) {
        return $this->bankRepo->delete($id);
    }

    public function verifyBVN($request) {
        return $this->bankRepo->verifyBVN($request);
    }
}