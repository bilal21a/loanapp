<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\API\ServiceRequest;
use App\Services\ShagoService;

class ShagoController extends Controller
{
    protected $shagoservice;

    public function __construct(ShagoService $shagoservice) {
        $this->shagoservice = $shagoservice;
    }

    public function buyAirtime(ServiceRequest $request) {
        return $this->shagoservice->buyAirtime($request);
    }

    public function buyData(ServiceRequest $request) {
        return $this->shagoservice->buyData($request);
    }

    public function cableSubscription(ServiceRequest $request) {
        return $this->shagoservice->cableSubscription($request);
    }

    public function powerSubscription(ServiceRequest $request) {
        return $this->shagoservice->powerSubscription($request);
    }

    public function databundlePurchase(ServiceRequest $request) {
        return $this->shagoservice->databundlePurchase($request);
    }

    public function transConfirmation(Request $request) {
        return $this->shagoservice->transConfirmation($request);
    }

    public function verify(Request $request) {
      return $this->shagoservice->verify($request);
    }
}
