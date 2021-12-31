<?php

namespace App\Http\Controllers\Operations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WithdrawalService;

class WithdrawalController extends Controller
{
    protected $withdrawalservice;

    public function __construct(WithdrawalService $withdrawalservice) {
        $this->withdrawalservice = $withdrawalservice; 
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
        return $this->withdrawalservice->index();
    }

    public function store(Request $request) {
        return $this->withdrawalservice->store($request);
    }

    public function find($id) {
        return $this->withdrawalservice->find($id);
    }
    public function update($id, Request $request) {
        return $this->withdrawalservice->update($id, $request);
    }
}
