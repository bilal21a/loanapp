<?php
namespace App\Payment;

use App\Account;
use App\Profile;
use App\Transaction;

class Payment {

    public function index() {
        return "Payment gateway";
    }
    
    public function sendSMS($to, $body) {
      $response = \Http::post('https://www.bulksmsnigeria.com/api/v1/sms/create', [
        'api_token' => env('SMS_API_TOKEN'),
        'from' => 'Mavunifs',
        'to' => $to,
        'body' => $body
      ]);
      //\Log::info((array)$response) ;
    }


    public function charge($details) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."charge",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "reference" => $this->refCode(),
                "currency" => "NGN",
                "email" => $details->email,
                "amount" => $this->naira_to_kobo($details->amount),
                "meta" => [
                  "customer_fields" => [
                    "display_name" => $details->desc,
                    "variable_name" => $details->desc,
                  ],
                ],
                "card" => [
                  "cvv" => $details->cardcvv,
                  "number" => $details->cardNumber,
                  "expiry_month" => $details->expiryMonth,
                  "expiry_year" => $details->expiryYear
                ],
                "pin" => $details->cardPin,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }
        //\Log::info("PAYSTACK", (array) $response);
        return json_decode($response, true);

    }

    public function bulkCharge($details) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."bulkcharge",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($details),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);
    }

    public function createTransferRecepient($details) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."transferrecipient",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "type" => "nuban",
                "currency" => "NGN",
                "name" => $details->name,
                "description" => $details->description,
                "account_number" => $details->accountNumber,
                "bank_code" => $details->bankCode,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);
    }

    public function initialize($details) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "reference" => $this->refCode(),
                "currency" => "NGN",
                "email" => $details->email,
                "amount" => 5000,
                 "meta" => [
                    "email" => $details->email,
                 ],
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
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

    public function verifyCard($transRef) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => config('payment.baseUrl')."/transaction/verify/".rawurlencode($transRef),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer ".config('payment.secretKey')."",
            "cache-control: no-cache",
          ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return $tranx = json_decode($response, true);

        if(!$tranx["status"]){
          die('API returned error: ' . $tranx["message"]);
        }

        if($tranx["data"]["status"] === "success"){

          return $tranx["data"];

        }
    }

    public function verifyBVN($bvn) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."bank/resolve_bvn/".rawurlencode($bvn),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
          //return response()->json($err);
          return array("status" => false, "message" => $err);
        }

        return $data = json_decode($response, true);
    }

    public function verifyBankAccount($accountNumber, $bankCode) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."bank/resolve?account_number=".rawurlencode($accountNumber)."&bank_code=".rawurlencode($bankCode),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
          //return response()->json($err);
          return array("status" => false, "message" => $err);
        }

        return $data = json_decode($response, true);
    }
    
    public function verifyTransaction($data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."transaction/verify/".$data->reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if($err) {
          return array("status" => false, "message" => $err);
        }
        return json_decode($response, true);
    }

    public function getBanks() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err) {
          //return response()->json($err);
          return array("status" => false, "message" => $err);
        }

        return $data = json_decode($response, true);
    }

    public function chargeAuthorization($data) {

        $curl = curl_init();

        $details =  array(
            'authorization_code' => $data->auth_code,
            'email' => $data->email,
            'amount' => $this->naira_to_kobo((float) str_replace(',', '', $data->amount)),
            'reference' => $data->reference
        );
        $headers = [
            "Authorization: Bearer ".config('payment.secretKey'),
            "Content-Type: application/json",
        ];
        curl_setopt($curl, CURLOPT_URL, config("payment.baseUrl")."transaction/charge_authorization");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($details));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);
    }

    public function transfer($details) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."transfer",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "source" => "balance",
                "reason" => $details->note,
                "amount" => $this->naira_to_kobo($details->amount),
                "recipient" => $details->recipient_code,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);
    }

    public function refCode() {
      return bin2hex(random_bytes(16));
    }

    public function naira_to_kobo($naira) {
      return $naira * 100;
    }
    
    
    public function sendPhone($request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."charge/submit_phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "phone" => $request->phone_number,
                "reference" => $request->reference,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);

    }

    public function sendOtp($request) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config("payment.baseUrl")."charge/submit_otp",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "otp" => $request->otp,
                "reference" => $request->reference,
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer ".config("payment.secretKey"),
                "content-type: application/json",
                "cache-control: no-cache",
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
          //die('Curl returned error: ' . $err);
          return array("status" => false, "message" => $err);
        }

        return json_decode($response, true);

    }

   public function webhook($request) {

    $input = $request->getContent();
    $paystack_key = config('payment.secretKey', env('PAYSTACK_SECRET_KEY'));

    if ($request->header('x-paystack-signature') !== hash_hmac('sha512', $input, $paystack_key)) {
      \Log::info("Exited because hash did not match");
        exit();
  
        http_response_code(200);
    }

      $transaction = Transaction::where("ref", $request->data["reference"])->first();
      $user =  Profile::find($transaction->user_profile_id);
      $account = Account::where('user_profile_id', $user->id)->first();
      $amount =  (new Account)->deposit(($request->data["amount"] / 100), $account->current_balance, $account->prev_balance);

      if($transaction->status === 'pending' || $transaction->status === 'failed') {
        $account->update([
          'amount' => ($request->data["amount"] / 100),
          'current_balance' => $amount["current_balance"],
          'prev_balance' => $amount["prev_balance"]
        ]);
        $transaction->update(["status" => "success"]);
        http_response_code(200);
      }
      else if($transaction->status === 'success') {
        http_response_code(200);
      }
      exit();

  }
}
