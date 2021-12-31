<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LoanService;
use App\Http\Requests\API\LoanRequest;

class LoanController extends Controller
{
    protected $loanservice;

    public function __construct(LoanService $loanservice) {
        $this->loanservice = $loanservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
        return $this->loanservice->index();
    }

    public function store(LoanRequest $request) {
        return $this->loanservice->store($request);
    }

    public function userLoans() {
        return $this->loanservice->userLoans();
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

    public function repayment(Request $request) {
        return $this->loanservice->repayment($request);
    }

    public function cancelRequest($id) {
        return $this->loanservice->cancelRequest($id);
    }
}
