<?php
namespace App\Monnify;

use App\EditEnv;

class Monnify {

    public function index() {
        return "Monnify channel";
    }

    public function getAuthToken() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config("monnify.baseUrl")."v1/auth/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic ".base64_encode(config("monnify.apiKey").":".config("monnify.secretKey")),
            "content-type: application/json",
            "cache-control: no-cache",
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return array("status" => false, "message" => $err);
        }

        $data = json_decode($response, true);

        if(array_key_exists("error", $data)) {
            return array("status" => false, "message" => $data);
        }

        if(array_key_exists("responseMessage", $data) && $data["responseMessage"] === "success") {
            return ["status" => true, "data" => $data["responseBody"]["accessToken"]];
            // $envdata = array("MONNIFY_TOKEN_KEY" => $data["responseBody"]["accessToken"]);
            // $update = (new EditEnv)->updateEnv($envdata);
            // $res = $update ? ["status" => true, "message" => "Token created"] :
            //                  ["status" => false, "message" => "Could not updated token"];
            // return $res;
        } else {
            return array("status" => false, "error" => $data["responseMessage"], "message" => $data["responseMessage"]);
        }

    }

    public function verifyBankAccount($request) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config("monnify.baseUrl")."v1/disbursements/account/validate?accountNumber=".rawurldecode($request->accountNumber)."&bankCode=".rawurldecode($request->bankCode),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic ".base64_encode(config("monnify.apiKey").":".config("monnify.secretKey")),
            "content-type: application/json",
            "cache-control: no-cache",
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return array("status" => false, "message" => $err);
        }

        $data = json_decode($response, true);

        if(array_key_exists("error", $data)) {
            return array("status" => false, "message" => $data);
        }

        if(array_key_exists("responseMessage", $data) && $data["responseMessage"] === "success") {
            return ["status" => true, "data" => $data["responseBody"]];
        } else {
            return array("status" => false, "error" => $data["responseMessage"], "message" => $data["responseMessage"]);
        }

    }

    public function reserveAccount($user) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("monnify.baseUrl")."v1/bank-transfer/reserved-accounts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "accountReference" => $this->refCode(),
                "accountName" => $user["first_name"]." ".$user["last_name"],
                "currencyCode" => "NGN",
                "contractCode" => config("monnify.contractCode"),
                "customerEmail" => $user["email"],
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".$user["access_token"],
                // "Authorization: Bearer ".config("monnify.token"),
                "Content-Type: application/json",
            ],

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        //\Log::info("API", (array) $response);
    
        if($err) {
            //return response()->json($err);
            return array("status" => false, "error" => $err, "message" => $err);
        }
        return json_decode($response, true);

    }

    public function deallocateAccount($accountNumber) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("monnify.baseUrl")."v1/bank-transfer/reserved-accounts/".rawurlencode($accountNumber),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => json_encode([
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("monnify.token"),
                "Content-Type: application/json",
            ],

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return response()->json($err);
        }

        return json_decode($response, true);
    }

    public function bankTransfer($request) {
        //\Log::info((array) $request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config("monnify.baseUrl")."v2/disbursements/single",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "amount" => $request->amount,
                "reference" => $this->refCode(),
                "narration" => $request->note,
                "destinationBankCode" => $request->bankCode,
                "destinationAccountNumber" => $request->accountNumber,
                "currency" =>  "NGN",
                "sourceAccountNumber" => config("monnify.walletID")
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic ".base64_encode(config("monnify.apiKey").":".config("monnify.secretKey")),
                "Content-Type: application/json",
            ],
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return array("status" => false, "message" => $err);
        }

        $data = json_decode($response, true);

        if(array_key_exists("error", $data)) {
            return array("status" => false, "message" => $data);
        }

        if(array_key_exists("responseMessage", $data) && $data["responseMessage"] === "success") {
            return ["status" => true, "data" => $data["responseBody"]];

        } else {
            return array("status" => false, "error" => $data["responseMessage"], "message" => $data["responseMessage"]);
        }

    }

    public function initializeTransaction($data) {
        $curl = curl_init();
        $details = \App\Profile::find($data->uid); //$this->profile->find($request->uid);

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("monnify.baseUrl")."v1/merchant/transactions/init-transaction",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "paymentReference" => $this->refCode(),
                "accountFullName" => $details->first_name." ".$details->last_name,
                "currencyCode" => "NGN",
                "contractCode" => config("monnify.contractCode"),
                "apiKey" => config("monnify.apiKey"),
                "customerEmail" => $details->email,
                "customerMobileNumber" => $details->phone_number,
                "amount" => 50.00,
                "paymentDescription" => "Card verification",
                "isTestMode" => true,
                 "meta" => [
                    "name" => $details->first_name." ".$details->last_name,
                 ],
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic ".base64_encode(config("monnify.apiKey").":".config("monnify.secretKey")),
                "Content-Type: application/json",
            ],

        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return response()->json($err);
        }

        $resData = json_decode($response, true);

        if(array_key_exists("responseMessage", $resData) && $resData["responseMessage"] === "success" ) {
        //    return $this->getReservedAccountTransactionStatus($resData["responseBody"]["transactionReference"]);
            return $resData;
        }

        if(array_key_exists("error", $resData)) {
            return $resData["error"];
        }
    }

    public function refCode() {
        return bin2hex(random_bytes(6));
    }

    public function getReservedAccountTransactions($ref) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config("monnify.baseUrl")."bank-transfer/reserved-accounts/transactions?accountReference=".rawurlencode($ref)."&page=0&size=10",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ".config("monnify.token"),
            "content-type: application/json",
            "cache-control: no-cache",
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return response()->json($err);
        }

        return $data = json_decode($response, true);
    }

    public function getReservedAccountTransactionStatus($transRef) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config("monnify.baseUrl")."v2/transactions/".rawurlencode($transRef),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ".config("monnify.token"),
            "content-type: application/json",
            "cache-control: no-cache",
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return response()->json($err);
        }

        return json_decode($response, true);
    }

    public function getBanks() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config("monnify.baseUrl")."banks",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ",
            "content-type: application/json",
            "cache-control: no-cache",
        ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
            return response()->json($err);
        }

        return $data = json_decode($response, true);
    }

    
    public function verifyBVN($bvn) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.monnify.com/api/v1/vas/verify-bvn",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "bvn" => $bvn,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic ".base64_encode(config("monnify.apiKey").":".config("monnify.secretKey")),
                "Content-Type: application/json",
            ],

        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        if($err) {
            return array("status" => false, "message" => $err);
        }

        $data = json_decode($response, true);

        if(array_key_exists("error", $data)) {
            return array("status" => false, "message" => $data);
        }

        if(array_key_exists("responseMessage", $data) && $data["responseMessage"] === "success") {
            return ["status" => true, "data" => $data["responseBody"]];
        } else {
            return array("status" => false, "error" => $data["responseMessage"], "message" => $data["responseMessage"]);
        }
    }
    
    public function getAccountNumbers($account_ref) {
            
        $access_token =  $this->getAuthToken();

        $response =  \Http::withHeaders([
            "Authorization" => "Bearer ". $access_token["data"],
            "Content-Type" => "application/json"
        ])->get(config("monnify.baseUrl")."v2/bank-transfer/reserved-accounts/".$account_ref)->json();

         return $response;

        // if($response["responseMessage"] === "success" && isset($response["responseBody"])) {
        //     return $response["responseBody"]["accounts"];
        // }
        
        // return ["status" => false];
    }
    
    public function updateBVN($account_ref, $bvn) {
            
        $access_token =  $this->getAuthToken();

        $response =  \Http::withHeaders([
            "Authorization" => "Bearer ". $access_token["data"],
            "Content-Type" => "application/json"
        ])->put(config("monnify.baseUrl")."v1/bank-transfer/reserved-accounts/update-customer-bvn/".$account_ref, ["bvn" => $bvn])->json();

         return $response;

        // if($response["responseMessage"] === "success" && isset($response["responseBody"])) {
        //     return $response["responseBody"];
        // }
        
        // return ["status" => false];
    }
    
    
    public function webhook($request) {
        //\Log::info($request->all());

        $transaction_keys = config('monnify.secretKey').'|'.$request['paymentReference'].'|'.$request['amountPaid'].'|'.$request['paidOn'].'|'.$request['transactionReference'];
        $transaction_hash = hash('SHA512', $transaction_keys);

        if($transaction_hash === $request['transactionHash']) {

            $account = Account::where('account_ref', '=', $request['product']['reference'])->first();
            $user =  Profile::find($account->user_profile_id);
            $amount=  (new Account)->deposit($request["amountPaid"], $account->current_balance, $account->prev_balance);


            $account->update([
                'amount' => $request["amountPaid"],
                'current_balance' => $amount["current_balance"],
                'prev_balance' => $amount["prev_balance"]
            ]);

            // Transaction::where('ref', $request['transactionReference'])->update([
            //     "status" => "success"
            // ]);

            $transaction =  (new Transaction)->transaction(
                $user,
                $request['transactionReference'],
                $request["amountPaid"],
                $account->id,
                'Mavunifs',
                "success",
                "credit",
                'Wallet Topup',
                "Mavunifs wallet topup"
            );
            \http_response_code(200);
            exit();
            //return response()->json('status', 201);
        }
    }

}
