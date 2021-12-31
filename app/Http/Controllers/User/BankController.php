<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BankService;
use App\Http\Requests\API\BankRequest;

class BankController extends Controller
{
    protected $bankservice;

    public function __construct(BankService $bankservice) {
        $this->bankservice = $bankservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
        return $this->bankservice->index();
    }

    public function newBank(BankRequest $request) {
        return $this->bankservice->newBank($request);
    }

    public function show($id) {
        return $this->bankservice->find($id);
    }

    public function update($id, Request $request) {
        return $this->bankservice->update($id, $request);
    }

    public function delete($id) {
        return $this->bankservice->delete($id);
    }
}
