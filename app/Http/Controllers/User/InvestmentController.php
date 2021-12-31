<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\InvestmentRequest;
use App\Services\InvestmentService;

class InvestmentController extends Controller
{
    protected $investmentservice;

    public function __construct(InvestmentService $investmentservice) {
        $this->investmentservice = $investmentservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
         return $this->investmentservice->index();
    }

    public function store(InvestmentRequest $request) {
        return $this->investmentservice->store($request);
    }

    public function show($id) {
        return $this->investmentservice->show($id);
    }

    public function update($id, InvestmentRequest $request) {
        return $this->investmentservice->update($id, $request);
    }

    public function delete($id) {
        return $this->investmentservice->delete($id);
    }

    public function invest($id) {
        return $this->investmentservice->invest($id);
    }
}
