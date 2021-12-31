<?php
namespace App\Services;

use App\Repositories\LoanRepository;

class LoanService {
  protected $loanRepository;

  public function __construct(LoanRepository $loanRepository) {
    $this->loanRepository = $loanRepository;
  }

  public function index() {
      return $this->loanRepository->index();
  }

  public function store($request) {
    return $this->loanRepository->store($request);
  }

  public function userLoans() {
    return $this->loanRepository->userLoans();
  }

  public function show($id) {
    return $this->loanRepository->show($id);
  }

  public function update($id, $request) {
    return $this->loanRepository->update($id, $request->all());
  }

  public function delete($id) {
    return $this->loanRepository->delete($id);
  }

  public function repayment($request) {
    return $this->loanRepository->repayment($request);
  }

  public function cancelRequest($id) {
    return $this->loanRepository->cancelRequest($id);
  }

}
