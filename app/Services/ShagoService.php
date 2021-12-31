<?php
namespace App\Services;

use App\Repositories\ShagoRepository;

class ShagoService {

  protected $shagorepository;

  public function __construct(ShagoRepository $shagorepository) {
    $this->shagorepository = $shagorepository;
  }

  public function buyAirtime($request) {
    return $this->shagorepository->buyAirtime($request);
  }

  public function buyData($request) {
    return $this->shagorepository->buyData($request);
  }

  public function cableSubscription($request) {
    return $this->shagorepository->cableSubscription($request);
  }

  public function powerSubscription($request) {
    return $this->shagorepository->powerSubscription($request);
  }

  public function databundlePurchase($request) {
    return $this->shagorepository->databundlePurchase($request);
  }

  public function transConfirmation($request) {
    return $this->shagorepository->transConfirmation($request);
  }

  public function verify($request) {
    return $this->shagorepository->verify($request);
  }
}
