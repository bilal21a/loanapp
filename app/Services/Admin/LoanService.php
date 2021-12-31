<?php
namespace App\Services\Admin;

use App\Repositories\Admin\LoanRepository;

class LoanService {
  protected $loanRepository;

  public function __construct(LoanRepository $loanRepository) {
    $this->loanRepository = $loanRepository;
  }

  public function index() {
    return $this->loanRepository->index();
  }

  public function create() {
    return $this->loanRepository->create();
  }

  public function store($request) {
    return $this->loanRepository->store($request);
  }

  public function show($id) {
    return $this->loanRepository->show($id);
  }

  public function edit($id) {
    return $this->loanRepository->edit($id);
  }

  public function update($id, $request) {
    return $this->loanRepository->update($id, $request->all());
  }

  public function delete($id) {
    return $this->loanRepository->delete($id);
  }

  public function userLoans() {
    return $this->loanRepository->userLoans();
  }

  public function approve($id, $request) {
    return $this->loanRepository->approve($id, $request);
  }

  public function manage($id) {
      return $this->loanRepository->manage($id);
  }

}
