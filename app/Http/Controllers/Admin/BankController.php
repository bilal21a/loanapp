<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\BankService;
use App\Profile;

class BankController extends Controller
{
    protected $bankservice;
    protected $profile;

    public function __construct(BankService $bankservice, Profile $profile) {
        $this->bankservice = $bankservice;
        $this->profile = $profile;
        $this->middleware(["assign.guard", "auth:admin"]);
    }

    public function index() {
        return $this->bankservice->index();
    }

    public function create($id) {
        return $this->bankservice->create($id);
    }

    public function newBank(Request $request) {
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
