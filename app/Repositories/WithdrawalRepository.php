<?php

namespace App\Repositories;

use App\Services\CardService;
use App\Services\AccountService;
use App\Profile;
use Carbon\Carbon;
use Validator;

class WithdrawalRepository {
    protected $cardservice;
    protected $profile;

    public function __construct(CardService $cardservice, Profile $profile, AccountService $accountservice) {
        $this->cardservice = $cardservice;
        $this->profile = $profile;
        $this->accountservice = $accountservice;
    }

    public function index() {
        return \Payment::index();
    }

    public function banks() {
        return \Payment::getBanks();
    }

    public function addCard($request) {

        $card = (object) array(
          "email" => $request->email,
          "amount" => 50,
          "desc" => "Card verification",
          "cardcvv"    => $request->cardcvv,
          "cardNumber"    => $request->cardNumber,
          "expiryMonth" => $request->expiryMonth,
          "expiryYear" => $request->expiryYear,
          "cardPin" => $request->cardPin,
        );
      $response = \Payment::charge($card);
      if($response["status"] && $response["data"]["authorization"]["reusable"]) {

        $uid = $uid = $this->profile->where("email", $response["data"]["customer"]["email"])->first()->id;
        $cardData = (object) array(
            "uid" => $uid,
            "authCode" => $response["data"]["authorization"]["authorization_code"],
            "cardType" => $response["data"]["authorization"]["card_type"],
            "bank" => $response["data"]['authorization']["bank"],
            "countryCode" => $response["data"]['authorization']["country_code"],
            "number" => $response["data"]['authorization']["last4"],
            "expYear" => $response["data"]['authorization']["exp_year"],
            "expMonth" => $response["data"]['authorization']["exp_month"],
        );
        return $this->cardservice->newCard($cardData);
      }
      else {
          return response()->json(["message" => $response["message"], "status" => false]);
      }
    }

    public function autoSave($request) {
        return $this->accountservice->auto_save($request);
    }


}
