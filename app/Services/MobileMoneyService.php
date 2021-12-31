<?php
namespace App\Services;

use App\Repositories\MobileMoneyRepository;

class MobileMoneyService
{
    protected $mobilemoneyRepo;

    public function __construct(MobileMoneyRepository $mobilemoneyRepo)
    {
        $this->mobilemoneyRepo = $mobilemoneyRepo;
    }

    public function buyAirtime($request)
    {
        return $this->mobilemoneyRepo->buyAirtime($request);
    }

    public function buyData($request)
    {
        return $this->mobilemoneyRepo->buyData($request);
    }

    public function cableSubscription($request)
    {
        return $this->mobilemoneyRepo->cableSubscription($request);
    }

    public function powerSubscription($request)
    {
        return $this->mobilemoneyRepo->powerSubscription($request);
    }

    public function pinPurchase($request)
    {
        return $this->mobilemoneyRepo->pinPurchase($request);
    }


    public function cheapData($request)
    {
        return $this->mobilemoneyRepo->cheapData($request);
    }

    public function productList($vendor) {
        return $this->mobilemoneyRepo->productList($vendor);
    }
}
