<?php 
namespace App\Services;

use App\Repositories\AccountCategoryRepository;

class AccountCategoryService {
    
    protected $accountCatRepo;

    public function __construct(AccountCategoryRepository $accountCatRepo) {
        $this->accountCatRepo = $accountCatRepo;
    }

    public function categories() {
        return $this->accountCatRepo->index();
    }

    public function createCategory($request) {
        return $this->accountCatRepo->create($request);
    }

    public function findCategory($id) {
        return $this->accountCatRepo->getCatgoery($id);
    }

    public function update($id, Request $request) {
        return $this->accountCatRepo->update($id, $request);
    }

    public function delete($id) {
        return $this->accountCatRepo->delete($id);
    }
}