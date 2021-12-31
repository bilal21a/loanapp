<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InvestmentRequest;
use App\Services\Admin\InvestmentService;
use App\Investment;

class InvestmentController extends Controller
{
    protected $investmentservice;

    public function __construct(InvestmentService $investmentservice) {
        $this->investmentservice = $investmentservice;
        $this->middleware(["assign.guard", "auth:admin"]);
    }

    public function index() {
         return $this->investmentservice->index();
    }

    public function create() {
         return $this->investmentservice->create();
    }

    public function store(InvestmentRequest $request) {
        return $this->investmentservice->store($request);
    }

    public function edit($id) {
        return $this->investmentservice->edit($id);
    }

    public function show($id) {
        return $this->investmentservice->show($id);
    }

    public function update($id, Request $request) {
        return $this->investmentservice->update($id, $request);
    }

    public function delete($id) {
        return $this->investmentservice->delete($id);
    }

    public function investors($id) {
        return $this->investmentservice->investors($id);
    }

    public function invest($id) {
        return $this->investmentservice->invest($id);
    }

    public function calc() {
        return (new Investment)->interestCalculator(4685000.00, 10, 1, "savings");
    }
}
