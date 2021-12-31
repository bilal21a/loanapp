<?php

namespace App\Services;
use App\Repositories\PaymentRepository;

class FundService {
    protected $paymentRepo; 

    public function __construct(PaymentRepository $paymentRepo) {
      $this->paymentRepo = $paymentRepo;
    }

    public function index() {
      return $this->paymentRepo->index();
    }

    public function verifyAccount($request) {
      return $this->paymentRepo->verifyAccount($request);
    }

    public function banks() {
      return $this->paymentRepo->banks();
    }

    public function addCard($request) {
      return $this->paymentRepo->addCard($request);
    }

    public function verifyCard($data) {
      return $this->paymentRepo->verifyCard($data);
    }

    public function quickSave($request) {
      return $this->paymentRepo->quickSave($request);
    }

    public function autoSave($request) {
      return $this->paymentRepo->autoSave($request);
    }

    public function bankTransfer($request) {
      return $this->paymentRepo->bankTransfer($request);
    }

    public function webhook($request) {
      return $this->paymentRepo->webhook($request);
    }
    
    
    public function webhookPaystack($request) {

      return $this->paymentRepo->webhookPaystack($request);

    }
}
