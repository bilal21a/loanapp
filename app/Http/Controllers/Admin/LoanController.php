<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\LoanService;

class LoanController extends Controller
{
    protected $loanservice;

    public function __construct(LoanService $loanservice) {
        $this->loanservice = $loanservice;
    }

    public function index() {
        return $this->loanservice->index();
    }

    public function create() {
        return $this->loanservice->create();
    }

    public function edit($id) {
        return $this->loanservice->edit($id);
    }

    public function store(Request $request) {
        return $this->loanservice->store($request);
    }

    public function show($id) {
        return $this->loanservice->show($id);
    }

    public function update($id, Request $request) {
        return $this->loanservice->update($id, $request);
    }

    public function delete($id) {
        return $this->loanservice->delete($id);
    }

    public function userLoans() {
        return $this->loanservice->userLoans();
    }


  public function approve($id, Request $request) {
    return $this->loanservice->approve($id, $request);
  }

  public function manage($id) {
        return $this->loanservice->manage($id);
  }
}
