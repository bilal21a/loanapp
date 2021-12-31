<?php 
namespace App\Services\Admin;

use App\Repositories\Admin\AccountCategoryRepository;

class AccountCategoryService {
    
    protected $accountCatRepo;

    public function __construct(AccountCategoryRepository $accountCatRepo) {
        $this->accountCatRepo = $accountCatRepo;
    }

    public function categories() {
        return $this->accountCatRepo->index();
    }

    public function create() {
        return $this->accountCatRepo->create();
    }

    public function createCategory($request) {
        return $this->accountCatRepo->store($request);
    }

    public function findCategory($id) {
        return $this->accountCatRepo->getCategory($id);
    }

    public function update($id, $request) {
        return $this->accountCatRepo->update($id, $request->all());
    }

    public function delete($id) {
        return $this->accountCatRepo->delete($id);
    }
}