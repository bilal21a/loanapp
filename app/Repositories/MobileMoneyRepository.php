<?php
namespace App\Repositories;

use App\Profile;
use App\Account;
use App\Savings;
use App\ServiceCategory;
use App\Transaction;
use App\Services;

class MobileMoneyRepository
{
    protected $user;
    protected $account;

    public function __construct(Profile $user, Account $account)
    {
        $this->user = $user;
        $this->account = $account;
    }

    public function buyAirtime($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= 500 || $account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng")."?";
        if (strtoupper($request->network) === "MTN") {
            $networkCode = 1;
        }

        switch (strtoupper($request->network)) {
      case 'MTN':
        $networkCode = 15;
        break;
      case 'AIRTEL':
        $networkCode = 1;
        break;
      case 'GLO':
        $networkCode = 6;
        break;
      case '9MOBILE':
        $networkCode = 2;
        break;
    }

        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "network" => $networkCode,
        "phone" => $request->phoneNumber,
        "amt" => $request->amount,
        "user_ref" => $this->refCode(),
        "jsn" => "json",
      ]
    ];
        $req =  $client->get($url, $params);

        $response = (object) \json_decode($req->getBody(), true);
        if ($response->code == 100) {
            $discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);

            $account->update([
        "amount" => $data["withdrawal_amount"],
        "current_balance" => $data["current_balance"],
        "prev_balance" => $data["prev_balance"],
      ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $this->refCode(),
                $discountedAmt,
                $account->id,
                $request->network,
                "success",
                "debit",
                "Withdrawal",
                "Airtime topup"
            );
            return response()->json(["status" => true, "message" => "Successful"]);
            // $resParams = [
      //   "query" => [
      //     "userid" => config("settings.mobileng.userid"),
      //     "pass" => config("settings.mobileng.password"),
      //     "transid" => $response->user_ref,
      //   ]
      // ];
      // $res = $client->get(config("settings.url_mobileng")."status?", $resParams);
      // $resp = (object) json_decode($res->getBody(), true);
      // if($resp->code === 100) {
      //   $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);

      //   $account->update([
      //     "amount" => $data["withdrawal_amount"],
      //     "current_balance" => $data["current_balance"],
      //     "prev_balance" => $data["prev_balance"],
      //   ]);
      //   $transaction =  (new Transaction)->transaction(
      //     $user, $response->exchangeReference,
      //     $discountedAmt, $account->id,
      //     $request->network, "success", "debit",
      //     "Mobile airtime subscription", "Airtime topup"
      //   );
      //   return response()->json(["status" => true, "message" => $response->message]);
      // }
      // return response()->json(["status" => false, "message" => $response->message]);
        }
        return response()->json(["status" => false, "message" => $response->message]);
    }

    public function buyData($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= 500 || $account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        switch (strtoupper($request->network)) {
      case 'MTN':
        $networkCode = 15;
        break;
      case 'AIRTEL':
        $networkCode = 1;
        break;
      case 'GLO':
        $networkCode = 6;
        break;
      case '9MOBILE':
        $networkCode = 2;
        break;
    }

        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng")."datatopup.php?";
        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "network" => $networkCode,
        "phone" => $request->phoneNumber,
        "amt" => $request->amount,
        "jsn" => "json",
        "user_ref" => $this->refCode(),
      ]
    ];
        $req =  $client->get($url, $params);
        $response = (object) \json_decode($req->getBody(), true);

        if ($response->code == 100) {
            // foreach($this->productList($request->network)->products as $data) {
            //   if($data["price"] == $request->amount) {
            //     $price = (object) $data;
            //   }
            // }
            // if(empty($price)) {
            //   return response()->json(["status" => false, "message" => "Invalid price"]);
            // }

            $discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);
            $account->update([
        "amount" => $data["withdrawal_amount"],
        "current_balance" => $data["current_balance"],
        "prev_balance" => $data["prev_balance"],
      ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $this->refCode(),
                $discountedAmt,
                $account->id,
                $request->network,
                "success",
                "debit",
                "Withdrawal",
                $request->amount." "."Mobile data topup"
            );
            return response()->json(["status" => true, "message" => $response->message]);
        }

        return response()->json(["status" => false, "message" => $response->message]);
    }

    public function cheapData($request)
    {
        //return response()->json($request->all());

        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= 500 || $account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng")."datashare?";
        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "network" => $request->network_code,
        "phone" => $request->phoneNumberNumber,
        "datasize" => $request->size,
        "jsn" => "json",
        "user_ref" => $this->refCode(),
      ]
    ];
        $req =  $client->get($url, $params);
        $response = (object) \json_decode($req->getBody(), true);

        if ($response->code == 100) {
            //$discountedAmt =  $this->account->cashBack($request->serviceId, $price["price"]);
            //$discount = ServiceCategory::find($request->serviceId)->service_charge;
            //$discountedAmt = ($request->amount * $discount) / 100;
            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);
            $account->update([
        "amount" => $data["withdrawal_amount"],
        "current_balance" => $data["current_balance"],
        "prev_balance" => $data["prev_balance"],
      ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $response->$this->refCode(),
                $request->amount,
                $account->id,
                $request->network,
                "success",
                "debit",
                "SME mobile data subscription",
                $request->package." "."Mobile data topup"
            );
            //     $details = (object) array(
            //       "type" => "debit",
            //       "email" => $user->email,
            //       "name" => $user->last_name,
            //       "amount" => $request->amount,
            //       "service" => "Mobile airtime",
            //       "status" => "success",
            //       "reference" => $this->refCode()
            //     );
            //   \dispatch(new TransactionJob($user->email, $details));
            return response()->json(["status" => true, "message" => $response->message]);
        }

        return response()->json(["status" => false, "message" => $response->message]);
    }


    public function cableSubscription($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= 500 || $account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        if (!$user->is_active) {
            return response()->json(["status" => false, "message" => "Account is inactive"]);
        }

        $client =  new \GuzzleHttp\Client();

        //    $verifyUser =  $client->get(config("settings.url_mobileng")."customercheck?", ["query" => [
        //      "userid" => config("settings.mobileng.userid"),
        //      "pass" => config("settings.mobileng.password"),
        //      "bill" => $request->type,
        //      "smartno" => $request->smartCardNo,
        //      "jsn" => "json"
        //    ]]);
        //    $verificationResp = (object) json_decode($verifyUser->getBody(), true);

        $serviceVendor = "multichoice?";
        $discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);

        if ($request->has("type") && strtolower($request->type) === "startimes") {
            $serviceVendor = "startimes?";
            $params  = [
          "query" => [
            "userid" => config("settings.mobileng.userid"),
            "pass" => config("settings.mobileng.password"),
            "amt" => $request->amount,
            "phone" => $request->phoneNumber,
            "smartno" => $request->smartCardNo,
            "user_ref" => $this->refCode(),
            "jsn" => "json",
          ]
        ];
        } else {
            $params  = [
          "query" => [
            "userid" => config("settings.mobileng.userid"),
            "pass" => config("settings.mobileng.password"),
            "smartno" => $request->smartCardNo,
            "phone" => $request->phoneNumber,
            "customer" => $request->customerName,
            "customernumber" => $request->customerNumber,
            "billtype" => $request->type,
            "invoice" => $request->invoice,
            "amt" => $request->amount,
            "user_ref" => $this->refCode(),
            "jsn" => "json",
          ]
        ];
        }

        $url = config("settings.url_mobileng").$serviceVendor;
        $req =  $client->get($url, $params);
        $response = (object) \json_decode($req->getBody(), true);

        if ($response->code == 100) {
            $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);
            $account->update([
          "amount" => $data["withdrawal_amount"],
          "current_balance" => $data["current_balance"],
          "prev_balance" => $data["prev_balance"],
        ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $this->refCode(),
                $discountedAmt,
                $account->id,
                $request->type,
                "success",
                "debit",
                "Withdrawal",
                $request->type." ".$request->type." "." subscription"
            );
            return response()->json(["status" => true, "message" => "Subscription successful", "ref_code" => $response->exchangeReference]);
        }
        return response()->json(["status" => false, "message" => $response->message]);

        return response()->json(["status" => false, "message" => "could not process request"]);
    }

    public function getPowerVendors()
    {
        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng")."power-lists?";
        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "jsn" => "json",
      ]
    ];
        $req =  $client->get($url, $params);

        $response = (object) \json_decode($req->getBody(), true);

        return response()->json($response);
    }

    public function powerSubscription($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= 500 || $account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        if (!$user->is_active) {
            return response()->json(["status" => false, "message" => "Account is inactive"]);
        }

        $client =  new \GuzzleHttp\Client();
        // $url = config("settings.url_mobileng")."power-validate?";
        // $params  = [
        //   "query" => [
        //     "userid" => config("settings.mobileng.userid"),
        //     "pass" => config("settings.mobileng.password"),
        //     "service" => $request->disco,
        //     "meterno" => $request->meterNo,
        //     "jsn" => "json",
        //   ]
        // ];
        // $req =  $client->get($url, $params);

        // $response = (object) \json_decode($req->getBody(), true);
        $reqParams = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "service" => $request->disco,
        "meterno" => $request->meterNo,
        "mtype" => $request->type,
        "amt" => $request->amount,
        "user_ref" =>$this->refCode(),
        "jsn" => "json"
      ]
    ];

        $purchase =  $client->get(config("settings.url_mobileng")."power-pay?", $reqParams);
        $purchaseResponse = (object) json_decode($purchase->getBody(), true);

        if ($purchaseResponse->code == 100) {
            $discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);

            $account->update([
        "amount" => $data["withdrawal_amount"],
        "current_balance" => $data["current_balance"],
        "prev_balance" => $data["prev_balance"],
      ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $purchaseResponse->CreditToken,
                $discountedAmt,
                $account->id,
                $request->disco,
                "success",
                "debit",
                "Withdrawal",
                "Meter recharge"
            );
            return response()->json(["status" => true, "message" => "Recharge successful", "token" => $purchaseResponse->CreditToken]);
        }
        // if($response->code == 100) {
        //   return response()->json(["status" => false, "message" => "Meter recharge successful"]);
        // }

        return response()->json(["status" => false, "message" => "Could not process data"]);
    }

    public function pinPurchase($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        if ($account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        if ($request->has("pinvendor") && $request->pinvendor === "waec") {
            $pinvendor = "waecdirect?";
            $amount = number_format(config("settings.neco_price"), 2);
        } else {
            $pinvendor = "neco?";
            $amount = number_format(config("settings.neco_price"), 2);
        }

        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng").$pinvendor;
        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "user_ref" => $this->refCode(),
        "jsn" => "json",
      ]
    ];
        $req =  $client->get($url, $params);

        $response = (object) \json_decode($req->getBody(), true);

        if ($response->code == 100) {
            $discountedAmt =  $this->account->cashBack($request->serviceId, $amount);
            $data =  $this->account->withdrawal($discountedAmt, $account->current_balance, $account->prev_balance);

            $account->update([
        "amount" => $data["withdrawal_amount"],
        "current_balance" => $data["current_balance"],
        "prev_balance" => $data["prev_balance"],
      ]);
            $transaction =  (new Transaction)->transaction(
                $user,
                $response->transId,
                $discountedAmt,
                $account->id,
                strtoupper($request->vendor),
                "success",
                "debit",
                "PIN",
                strtoupper($request->vendor)." "."PIN purchase"
            );
            return response()->json([
        "status" => true,
        "message" => "PIN purchase successful",
        "data" => $response,
      ]);
        }

        return response()->json(["status" => false, "message" => "Transaction failed"]);
    }

    public function productList($vendor)
    {
        $client =  new \GuzzleHttp\Client();
        $url = config("settings.url_mobileng")."get-items?";
        $params  = [
      "query" => [
        "userid" => config("settings.mobileng.userid"),
        "pass" => config("settings.mobileng.password"),
        "service" => $vendor,
        "jsn" => "json",
      ]
    ];
        $req =  $client->get($url, $params);

        $result = (object) json_decode($req->getBody(), true);
        return response()->json($result);
    }

    public function refCode()
    {
        return bin2hex(random_bytes(4));
    }

    public function verify($request)
    {
        $client =  new \GuzzleHttp\Client([
            "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
            //"headers" =>  ["hashkey" => config("settings.shagoKey")]
        ]);
        $url = config("settings.url_mobileng");

        if ($request->service === 'data') {
            $url = config("settings.url_mobileng").'datatopup.php?';
            $params  = [
            "query" =>
            [
              "userid" => config("settings.mobileng.userid"),
              "pass" => config("settings.mobileng.password"),
              "network" => $networkCode,
              "phone" => $request->phoneNumber,
              "amt" => $request->amount,
              "jsn" => "json",
              "user_ref" => $this->refCode(),
            ]
          ];
        }

        if ($request->service === 'electricity') {
            $url = config("settings.url_mobileng")."power-validate?";
            $params  = [
            "query" => [
              "userid" => config("settings.mobileng.userid"),
              "pass" => config("settings.mobileng.password"),
              "service" => $request->disco,
              "meterno" => $request->meterNo,
              "jsn" => "json",
            ]
          ];
        }


        if ($request->service === 'data-bundle') {
            if ($request->has("type") && strtoupper($request->type) === "SMILE") {
                $params = [ "form_params" => ["serviceCode" => "SMV", "account" => $request->account ] ];
            }

            if ($request->has("type") && strtoupper($request->type) === "SPECTRANET") {
                $params = [ "form_params" => ["serviceCode" => "SPV"] ];
            }
        }

        if ($request->service === 'cable-tv') {
            $params  = [
            "query" =>
            [
              "userid" => config("settings.mobileng.userid"),
              "pass" => config("settings.mobileng.password"),
              "bill" => $request->type,
              "smartno" => $request->smartCardNo,
              "jsn" => "json"
            ]
          ];
        }

        $req =  $client->post($url, $params);
        $response = (object) \json_decode($req->getBody(), true);
        // \Log::info((array) $response);
        if ($response->status == 200) {
            return response()->json(['status' => true, 'data' => $response]);
        }

        return response()->json(['status' => false, 'message' => $response->message]);
    }
}
