<?php
namespace App\Repositories;

use App\Profile;
use App\Account;
use App\Savings;
use App\Transaction;
use App\Services;
use App\Http\Resources\ProfileResource;
use App\Jobs\ElectricityJob;
use Carbon\Carbon;
use App\Jobs\TransactionJob;

class ShagoRepository
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
        $account = Account::where("user_profile_id", $user->id)->first();

        if ($account->current_balance <= $request->amount) {
            return response()->json(["status" => false, "action" => "Credit  wallet", "message" => "Insufficient fund"]);
        }

        $url = 'https://api.sandbox.africastalking.com/version1/airtime/send';

        $aParams = [
            "username" => "sandbox",
            "recipients" => json_encode([
                ["phoneNumber" => '+27'.str_replace(' ', '', $request->phoneNumber), "amount" => "ZAR ".$request->amount]
            ]),
        ];

       $response = \Http::asForm()->withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded",
            "Accept" => "application/json",
            "apiKey" => "d387e4e5dabc238c0589f0427d90ac4ee106ac4bfa8ee8b187ddda353b73b55f",
       ])->post($url, $aParams);
        // \Log::info($response);
        $transaction =  Transaction::create([
            "user_profile_id" => $user->id,
            "ref" => $this->refCode(),
            "amount" => $request->amount,
            "account_id" => $account->id,
            "vendor" => 'mavunif',//$request->network,
            "beneficiary" => $user->first_name." ".$user->last_name,
            "status" => "pending",
            "type" => "debit",
            "sub_type" => "Airtime",
            "description" => "Airtime Topup to ".$request->phoneNumber
        ]);

        if ($response['errorMessage'] === 'None' && !empty($response['responses'])) {

            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);

            $account->update([
                "amount" => $data["withdrawal_amount"],
                "current_balance" => $data["current_balance"],
                "prev_balance" => $data["prev_balance"],
            ]);

            $transaction =  (new Transaction)->transaction(
                $user,
                $this->refCode(),
                $request->amount,
                $account->id,
                'Mavunif',
                'success',
                "debit",
                "Airtime topup",
                "Airtime mobile topup on ".$request->phoneNumber
            );

            //\dispatch(new TransactionJob($user->email, $details));

            if ($request->has("service_charge") && $request->service_charge > 0) {
                $account->update([
                    "amount" => $request->service_charge,
                    "current_balance" => $account->current_balance - $request->service_charge,
                    "prev_balance" => $account->amount,
                ]);
                $transaction =  Transaction::create([
                    "user_profile_id" => $user->id,
                    "ref" => $this->refCode(),
                    "amount" => $request->service_charge,
                    "account_id" => $account->id,
                    "vendor" => "Mavunifs",
                    "beneficiary" => "Mavunifs",
                    "status" => "success",
                    "type" => "debit",
                    "sub_type" => "Service charge",
                    "description" => "Service/Convenience charge  for ".$request->network." airtime topup"
                ]);
                $chargeDdetails = (object) array(
                    "type" => "debit",
                    "email" => $user->email,
                    "name" => $user->last_name,
                    "amount" => $request->service_charge,
                    "service" => "Service/Convenience Charge for ".$request->network." airtime topup",
                    "status" => "success",
                    "reference" => $this->refCode()
                );
                \dispatch(new TransactionJob($user->email, $chargeDdetails));
            }

            return response()->json(["status" => true, "message" => "Airtime purchase to ".$request->phoneNumber." successful", "user" => new ProfileResource($user)]);
        }
        return response()->json(["status" => false, "message" => $response['errorMessage']]);
    }

    public function buyData($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        $totalTransaction = Transaction::where("user_profile_id", $user->id)->sum("amount");

        if (empty($user->kyc->bvn) && $totalTransaction >= 2000) {
            return response()->json(["status" => false, "message" => "Verify bvn to continue", "action" => "bvn"]);
        }

        if ($account->current_balance <= ($request->amount + $request->service_charge)) {
            return response()->json(["status" => false, "action" => "Credit  wallet",  "message" => "Insufficient fund"]);
        }

        $client =  new \GuzzleHttp\Client([
      //"headers" =>  ["hashkey" => config("settings.shagoKey")]
     "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
    ]);
        $url = config("settings.url_shago");

        $resParams = [
          "form_params" => [
            "serviceCode" => 'BDA',
            "network" => strtoupper($request->network),
            "phone" => $request->phoneNumber,
            "bundle" => $request->bundle,
            "amount" => $request->amount,
            "package" => $request->bundle,
            "request_id" => $this->refCode()
          ]
        ];
        $res = $client->post(config("settings.url_shago"), $resParams);
        $resp = (object) json_decode($res->getBody(), true);

        if ($resp->status == 200 || $resp->status == 400) {
            $transactionStatus = $resp->status == 400 ? "pending": "success";
            $service = Services::where('name', $request->network)->first();
            $discountedAmt =  $this->account->cashBack($service->id, $request->amount);
            //$discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);

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
                $transactionStatus,
                "debit",
                "Mobile data",
                $request->network." ".$request->package." top up"
            );
            $details = (object) array(
                "type" => "debit",
                "email" => $user->email,
                "name" => $user->last_name,
                "amount" => $request->service_charge,
                "service" => $request->network." mobile data topup",
                "status" => "success",
                "reference" => $this->refCode()
            );
            \dispatch(new TransactionJob($user->email, $details));

            if ($request->has("service_charge") && $request->service_charge > 0) {
                $account->update([
                    "amount" => $request->service_charge,
                    "current_balance" => $account->current_balance - $request->service_charge,
                    "prev_balance" => $account->amount,
                 ]);
                $transaction =  Transaction::create([
                    "user_profile_id" => $user->id,
                    "ref" => $this->refCode(),
                    "amount" => $request->service_charge,
                    "account_id" => $account->id,
                    "vendor" => "Mavunifs",
                    "beneficiary" => "Mavunifs",
                    "status" => "success",
                    "type" => "debit",
                    "sub_type" => "Service charge",
                    "description" => "Service/Convenience charge  for ".$request->network." mobile data topup"
                  ]);
                $chargeDdetails = (object) array(
                      "type" => "debit",
                      "email" => $user->email,
                      "name" => $user->last_name,
                      "amount" => $request->service_charge,
                      "service" => "Service/Convenience Charge for ".$request->network." mobile data topup",
                      "status" => "success",
                      "reference" => $this->refCode()
                  );
                \dispatch(new TransactionJob($user->email, $chargeDdetails));
            }
            return response()->json([
                "status" => true, "message" => "Data topup ".$transactionStatus,
                "package" => $request->package,
                "data" => $resp
            ]);
        }
        return response()->json(["status" => false, "message" => "An network error occured"]);
    }

    public function cableSubscription($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $this->account->where("user_profile_id", $user->id)->first(); //$user->accounts->first();

        $totalTransaction = Transaction::where("user_profile_id", $user->id)->sum("amount");

        if (empty($user->kyc->bvn) && $totalTransaction >= 2000) {
            return response()->json(["status" => false, "message" => "Verify bvn to continue", "action" => "bvn"]);
        }

        if ($account->current_balance <= ($request->amount + $request->service_charge)) {
            return response()->json(["status" => false, "action" => "Credit  wallet",  "message" => "Insufficient fund"]);
        }

        if (!$user->is_active) {
            return response()->json(["status" => false, "message" => "Account is inactive"]);
        }

        $client =  new \GuzzleHttp\Client([
      //"headers" =>  ["hashkey" => config("settings.shagoKey")]
      "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
    ]);
        $url = config("settings.url_shago");

        if ($request->has("type") && $request->type === "STARTIMES") {
            $params  = [
        "form_params" => [
          "serviceCode" => 'GDB',
          "amount" => $request->amount,
          "customerName" => $request->customerName,
          "type" => $request->type,
          "packagename" => $request->type,
          "smartCardNo" => $request->smartCardNo,
          "request_id" => $this->refCode(),
        ]
      ];
        }

        if ($request->has("type") && $request->type === "GOTV") {
            $params  = [
        "form_params" => [
          "serviceCode" => "GDB",
          "amount" => $request->amount,
          "customerName" => $request->customerName,
          "type" => strtoupper($request->type),
          "packagename" => $request->packageName,
          "smartCardNo" => $request->smartCardNo,
          "productsCode" => $request->productCode,
          "period" => $request->period,
          "hasAddon" => "0",
          "request_id" => $this->refCode(),
        ]
      ];
        }

        if ($request->has("type") && $request->type === "DSTV") {
            $params  = [
        "form_params" => [
          "serviceCode" => "GDB",
          "smartCardNo" => $request->smartCardNo,
          "customerName" => $request->customerName,
          "type" => strtoupper($request->type),
          "amount" => $request->amount,
          "packagename" => $request->packageName,
          "productsCode" => $request->productCode,
          "period" => $request->period,
          "hasAddon" => $request->hasAddon,
          "request_id" => $this->refCode(),
        ]
      ];
        }
        $service = Services::where('name', $request->type)->first();
        $discountedAmt =  $this->account->cashBack($service->id, $request->amount);
        $transaction =  Transaction::create([
        "user_profile_id" => $user->id,
        "ref" => $this->refCode(),
        "amount" => $request->amount,
        "account_id" => $account->id,
        "vendor" => $request->type,
        "beneficiary" => $user->first_name." ".$user->last_name,
        "status" => "pending",
        "type" => "debit",
        "sub_type" => "cable",
        "description" => "Cable tv subscription ".$request->type
      ]);
        $req =  $client->post($url, $params);

        $response = (object) \json_decode($req->getBody(), true);
        if ($response->status == 200 || $response->status ==  400) {
            $transactionStatus = $response->status == 400 ? "pending": "success";
            // $discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);

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
                $transactionStatus,
                "debit",
                "Cable TV",
                $request->type." "." subscription"
            );

            //$transRef = $request->type === "STARTIMES" ? null : $response->transId;
            return response()->json([
        "status" => true, "message" => "Subscription ".$transactionStatus,
        "data" => $response
      ]);
        }


        return response()->json(["status" => false, "message" => "Subscription failed"]);
    }

    public function powerSubscription($request)
    {
        $user = auth()->guard("profile")->user();
        //\Log::info((array) $user);
        $account = $this->account->where("user_profile_id", $user->id)->first();

        $totalTransaction = Transaction::where("user_profile_id", $user->id)->sum("amount");

        if (empty($user->kyc->bvn) && $totalTransaction >= 2000) {
            return response()->json(["status" => false, "message" => "Verify bvn to continue", "action" => "bvn"]);
        }

        if ($account->current_balance <= ($request->amount + $request->service_charge)) {
            return response()->json(["status" => false,  "action" => "Credit  wallet", "message" => "Insufficient fund"]);
        }

        if (!$user->is_active) {
            return response()->json(["status" => false, "message" => "Account is inactive, contact support for assistance"]);
        }

        $service = Services::where('name', $request->disco)->first();
        $discountedAmt =  $this->account->cashBack($service->id, $request->amount);

        $client =  new \GuzzleHttp\Client([
      // "headers" =>  ["hashkey" => config("settings.shagoKey")]
     "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
    ]);
        $url = config("settings.url_shago");

        $reqParams = [
      "form_params" => [
        "serviceCode" => 'AOB',
        "meterNo" => $request->meterNo,
        "disco" => $request->disco,
        "type" => $request->type,
        "amount" => $request->amount,
        "phonenumber" => $request->phoneNumber,
        "name" => $request->customerName,
        "address" => $request->customerAddress,
        "request_id" => $this->refCode(),
      ]
    ];
        $purchase =  $client->post(config("settings.url_shago"), $reqParams);
        $purchaseResponse = (object) json_decode($purchase->getBody(), true);
        $transaction =  Transaction::create([
        "user_profile_id" => $user->id,
        "ref" => $this->refCode(),
        "amount" => $request->amount,
        "account_id" => $account->id,
        "vendor" => $request->disco,
        "beneficiary" => $user->first_name." ".$user->last_name,
        "status" => "pending",
        "type" => "debit",
        "sub_type" => "Meter recharge",
        "description" => "Meter Topup "
      ]);
        if (isset($purchaseResponse->status) && $purchaseResponse->status == 300) {
            $transaction->update(["status" => "failed"]);
            return response()->json(["status" => false, "message" => "Meter topup faied / rejected"]);
        }

        if ($purchaseResponse->status == 200 || $purchaseResponse->status == 400) {
            $transactionStatus = $purchaseResponse->status == 400 ? "pending": "success";
            $description = $purchaseResponse->status == 200 ? "Meter topup token: ".$purchaseResponse->token : "Meter topup ";
            $creditToken = $purchaseResponse->status == 200 ? $purchaseResponse->token : "processing";
            //$discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);

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
                $purchaseResponse->disco,
                $transactionStatus,
                "debit",
                "Meter recharge",
                "Meter Topup ".$purchaseResponse->token
            );
            return response()->json([
        "status" => true, "message" => "Recharge successful",
        "disco" => $purchaseResponse->disco,
        "data" => $purchaseResponse
      ]);

            //return response()->json(["status" => true, "message" => "Meter recharge successful", "transId" => $purchaseResponse->transId, "token" => $purchaseResponse->token]);
        }

        return response()->json(["status" => false, "message" => "Meter Topup failed"]);
    }

    public function databundlePurchase($request)
    {
        $user =  auth()->guard("profile")->user();
        $account = $user->accounts->first();

        $totalTransaction = Transaction::where("user_profile_id", $user->id)->sum("amount");

        if (empty($user->kyc->bvn) && $totalTransaction >= 2000) {
            return response()->json(["status" => false, "message" => "Verify bvn to continue", "action" => "bvn"]);
        }

        if (empty($request->type)) {
            return response()->json(["status" => false, "message" => "Service type is required"]);
        }

        if (!$user->is_active) {
            return response()->json(["status" => false, "message" => "Inactive account, contact admin to activate"]);
        }

        if ($account->current_balance <= ($request->amount + $request->service_charge)) {
            return response()->json(["status" => false,  "action" => "Credit  wallet", "message" => "Insufficient fund"]);
        }

        $client =  new \GuzzleHttp\Client([
    //  "headers" =>  ["hashkey" => config("settings.shagoKey")]
      "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
    ]);

        $url = config("settings.url_shago");
        if ($request->type === "SMILE") {
            $params  = [
        "form_params" => [
          "serviceCode" => "SMB",
          "account" => $request->account,
          "amount" => $request->amount,
          "bundle" => $request->bundle,
          "package" => $request->bundle,
          "productsCode" => $request->productCode,
          "type" => "SMILE_BUNDLE",
          "request_id" => $this->refCode(),
        ]
      ];
        }

        if (strtoupper($request->type) === "SPECTRANET") {
            $params  = [
        "form_params" => [
          "serviceCode" => "SPB",
          "amount" => $request->amount,
          "pinNo" => "1", //$request->pinNo,
          "type" => strtoupper($request->type),
          "request_id" => $this->refCode(),
        ]
      ];
        }

        $purchase = $client->post($url, $params);
        $purchaseResponse = (object) json_decode($purchase->getBody(), true);

        //\Log::info((array) $purchaseResponse);

        if ($purchaseResponse->status ==  200 || $purchaseResponse->status == 400) {
            $transactionStatus = $purchaseResponse->status == 400 ? "pending": "success";
            //$discountedAmt =  $this->account->cashBack($request->serviceId, $request->amount);
            $service = Services::where('name', $request->type)->first();
            $discountedAmt =  $this->account->cashBack($service->id, $request->amount);
            $data =  $this->account->withdrawal($request->amount, $account->current_balance, $account->prev_balance);

            if ($purchaseResponse->status == 200) {
                //$data = explode(",", $purchaseResponse->pin);
                $pin = "PIN "." ".$purchaseResponse->pin[0]["pin"].' '."Serial ".$purchaseResponse->pin[0]["serial"];
            } else {
                $pin = "Check your email for pin";
            }

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
                $transactionStatus,
                "debit",
                "Internet data bundle",
                $request->type." data bunlde"
            );
            $details = (object) array(
          "type" => "debit",
          "email" => $user->email,
          "name" => $user->last_name,
          "amount" => $request->amount,
          "service" => $request->type." Internet bundle subscription",
          "status" => "success",
          "reference" => $this->refCode()
      );
            \dispatch(new TransactionJob($user->email, $details));

            if ($request->has("service_charge") && $request->service_charge > 0) {
                $account->update([
            "amount" => $request->service_charge,
            "current_balance" => $account->current_balance - $request->service_charge,
            "prev_balance" => $account->amount,
         ]);
                $transaction =  Transaction::create([
            "user_profile_id" => $user->id,
            "ref" => $this->refCode(),
            "amount" => $request->service_charge,
            "account_id" => $account->id,
            "vendor" => "Mavunifs",
            "beneficiary" => "Mavunifs",
            "status" => "success",
            "type" => "debit",
            "sub_type" => "Service charge",
            "description" => "Service/Convenience charge  for ".$request->type." subscription"
          ]);
          $chargeDdetails = (object) array(
              "type" => "debit",
              "email" => $user->email,
              "name" => $user->last_name,
              "amount" => $request->service_charge,
              "service" => "Service/Convenience Charge for ".$request->type." subscription",
              "status" => "success",
              "reference" => $this->refCode()
          );
                \dispatch(new TransactionJob($user->email, $chargeDdetails));
            }

            return response()->json([
        "status" => true,
        "message" => $request->type." "."data subscription ".$transactionStatus,
        "data" => $purchaseResponse
      ]);
        }

        return response()->json(["status" => false, "message" => "Transaction could not be completed"]);
    }

    public function refCode()
    {
        return bin2hex(random_bytes(9));
    }

    public function refCodeDigit()
    {
        return mt_rand(000000, 999999);
    }

    public function verify($request)
    {
        $client =  new \GuzzleHttp\Client([
        "headers" =>  ["email" => "test@shagopayments.com", "password" => "test123"]
      //"headers" =>  ["hashkey" => config("settings.shagoKey")]
    ]);
        $url = config("settings.url_shago");
        if ($request->service === 'data') {
            $params  = [
        "form_params" => [
          "serviceCode" => "VDA",
          "network" => strtoupper($request->network),
          "phone" => '07085840467',
        ]
      ];
        }

        if ($request->service === 'electricity') {
            $params  = [
        "form_params" => [
          "serviceCode" => "AOV",
          "disco" => $request->disco,
          "meterNo" => $request->meterNo,
          "type" => strtoupper($request->type),
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
            $params = [
        "form_params" => [
        "serviceCode" => "GDS",
        "smartCardNo" => $request->smartCardNo,
        "type" => strtoupper($request->type)
        ]
      ];
        }

        $req =  $client->post($url, $params);
        $response = (object) \json_decode($req->getBody(), true);
        \Log::info((array) $response);
        if ($response->status == 200) {
            return response()->json(['status' => true, 'data' => $response]);
        }

        return response()->json(['status' => false, 'message' => $response->message]);
    }

    public function transConfirmation(Request $request)
    {
        if ($request->status == 200) {
            $transaction =  Transaction::where("ref", $request->refrenceId)->first();
            $transaction->update(["status" => "success"]);
        }

        if ($request->status == 300) {
            $this->account->refund($request->referenceId);
        }
    }
}
