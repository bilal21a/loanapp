<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\API\ServiceRequest;
use App\Services\MobileMoneyService;

class MobileMoneyController extends Controller
{
    protected $mobilemoneyservice;

    public function __construct(MobileMoneyService $mobilemoneyservice) {
        $this->mobilemoneyservice = $mobilemoneyservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function buyAirtime(ServiceRequest $request) {
        return $this->mobilemoneyservice->buyAirtime($request);
    }

    public function buyData(ServiceRequest $request) {
        return $this->mobilemoneyservice->buyData($request);
    }

    public function cableSubscription(ServiceRequest $request) {
        return $this->mobilemoneyservice->cableSubscription($request);
    }

    public function powerSubscription(ServiceRequest $request) {
        return $this->mobilemoneyservice->powerSubscription($request);
    }

    public function pinPurchase(ServiceRequest $request) {
        return $this->mobilemoneyservice->pinPurchase($request);
    }

    public function cheapData(Request $request) {
        return $this->mobilemoneyservice->cheapData($request);
    }

    public function productList($vendor) {
        return $this->mobilemoneyservice->productList($vendor);
    }
}
