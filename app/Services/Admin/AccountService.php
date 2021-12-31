<?php 
namespace App\Services\Admin;

use App\Repositories\AccountRepository;

class AccountService {
    
    protected $accountRepo;

    public function __construct(AccountRepository $accountRepo) {
        $this->accountRepo = $accountRepo;
    }

    public function accounts() {
        return $this->accountRepo->index();
    }

    public function createAccount($request) {
        return $this->accountRepo->create($request);
    }

    public function findAccount($id) {
        return $this->accountRepo->getAccount($id);
    }

    public function update($id, Request $request) {
        return $this->accountRepo->update($id, $request);
    }

    public function delete($id) {
        return $this->accountRepo->delete($id);
    }

    public function deactivateAccount($id) {
        return $this->accountRepo->freezeAccount($id);
    }

    public function quick_save($request) {
        return $this->accountRepo->quickSave($request);
    }

    public function auto_save($request) {
        return $this->accountRepo->autoSave($request);
    }

    public function withdrawal($request) {
        return $this->accountRepo->withdraw($request);
    }

    public function settings($request) {
        return $this->accountRepo->autoSaveConfig($request); 
    }
    
}