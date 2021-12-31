<?php
namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService {

    protected $accountRepo;

    public function __construct(AccountRepository $accountRepo) {
        $this->accountRepo = $accountRepo;
    }

    public function accounts() {
        return $this->accountRepo->index();
    }

    public function store($request) {
        return $this->accountRepo->create($request);
    }

    public function findAccount($id) {
        return $this->accountRepo->getAccount($id);
    }

    public function update($id, $request) {
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

    public function addCard($request) {
        return $this->accountRepo->addCard($request);
    }

    public function walletTopup($request) {

        return $this->accountRepo->walletTopup($request);

    }

    public function withdrawToWallet($request) {

        return $this->accountRepo->withdrawToWallet($request);

    }

}
