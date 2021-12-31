<?php

namespace App\Repositories;

use App\Http\Resources\ProfileResource;
use App\Services\CardService;
use App\Services\AccountService;
use App\Profile;
use App\Transaction;
use App\Account;
use Carbon\Carbon;
use Validator;
use App\Http\Resources\ProfileResource;

class PaymentRepository
{
    protected $cardservice;
    protected $profile;

    public function __construct(
        CardService $cardservice,
        Profile $profile,
        AccountService
        $accountservice
    ) {
        $this->cardservice = $cardservice;
        $this->profile = $profile;
        $this->accountservice = $accountservice;
    }

    public function index()
    {
        return \Payment::index();
    }

    public function banks()
    {
        return \Payment::getBanks();
    }

    public function addCard($request)
    {
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
        if ($response["status"] && $response["data"]["authorization"]["reusable"]) {
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
        } else {
            return response()->json(["message" => $response["message"], "status" => false]);
        }
    }

    public function autoSave($request)
    {
        return $this->accountservice->auto_save($request);
    }

    public function verifyAccount($request)
    {
        return \Monnify::verifyBankAccount($request);
    }


    public function bankTransfer($request)
    {
        $user =  Profile::find($request->uid);
        $account = Account::where('user_profile_id', '=', $request->uid)->first();
        $amt = $request->amount; //$request->charge;
        //$fee = 0;
        
        // if($request->has("get_fee") && $request->get_fee === true) {
        //     $fee = 50;
        //     return response()->json(["status" => true, "action" => "fee", "data" => $fee, "message" => "You will be charged ".$fee." for this transaction"]);
        // }
        
        if ($amt >= $account->current_balance) {
            return response()->json(["status" => false, "action" => true, "message" => "Insufficient fund"]);
        }

        if ($request->amount < 50) {
            return response()->json(["status" => false, "message" =>  "Minimum transfer amount is 50"]);
        }

        if ($request->amount >= 100001) {
            return response()->json(["status" => false, "message" =>  "Maximum transfer amount per transaction is 100,000"]);
        }

        $response = \Monnify::bankTransfer($request);

        if ($response["status"]) {
            $amount =  (new Account)->withdrawal($response["data"]["amount"], $account->current_balance, $account->prev_balance);


            $account->update([
                'amount' => $request->amount,
                'current_balance' => $account->current_balance - $request->amount,
                'prev_balance' => $account->current_balance,
            ]);
            
            $transaction =  Transaction::create([
                    "user_profile_id" => $user->id,
                    "ref" => $response["data"]["reference"],
                    "account_id" => $account->id,
                    "amount" => $request->amount,
                    "type" => "debit",
                    "sub_type" => "wallet",
                    "beneficiary" => $user->first_name.' '.$user->last_name,
                    "vendor" => "Mavunifs",
                    "description" => "N".$request->amount." to ".$request->accountHolder." ".$request->accountNumber." ".$request->bank,
                    "status" => "success",
                ]);
            
            if ($request->has("charge") && !empty($request->charge)) {
                $account->update([
                    'amount' => $request->charge,
                    'current_balance' => $account->current_balance - $request->charge,
                    'prev_balance' => $account->current_balance
                ]);
                
                Transaction::create([
                    "user_profile_id" => $user->id,
                    "ref" => \Monnify::refCode(),
                    "account_id" => $account->id,
                    "amount" => $request->charge,
                    "type" => "debit",
                    "sub_type" => "wallet",
                    "beneficiary" => "Mavunifs",
                    "vendor" => "Mavunifs",
                    "description" => "withdrawal charge",
                    "status" => "success",
                ]);
            }


            return response()->json([
                "status" => true,
                "message" => "Transfer successful",
                "amount" => $response['data']['amount'],
                "user" => new ProfileResource($user),
                "transaction" => $transaction
            ]);

            $transaction =  (new Transaction)->transaction(
                $user,
                $response["data"]["reference"],
                $response["data"]["amount"],
                $account->id,
                "Kredda",
                "success",
                "credit",
                "Fund Transfer",
                $request->amount." to ".$request->accountHolder." ".$request->accountNumber." ".$request->bank
            );

            return response()->json([
                "status" => true,
                "message" => "success",
                "amount" => $response['data']['amount'],
                "user" => new ProfileResource($user),
                "transaction" => $transaction
            ]);
        } else {
            return response()->json(["status" => false, "message" => $response['message']]);
        }
    }

    public function webhook($request)
    {
        $transaction_keys = config('monnify.secretKey').'|'.$request['paymentReference'].'|'.$request['amountPaid'].'|'.$request['paidOn'].'|'.$request['transactionReference'];
        $transaction_hash = hash('SHA512', $transaction_keys);

        if ($transaction_hash === $request['transactionHash']) {
            $account = Account::where('account_ref', '=', $request['product']['reference'])->first();
            $user =  Profile::find($account->user_profile_id);
            $amount =  (new Account)->deposit($request["amountPaid"], $account->current_balance, $account->prev_balance);


            $account->update([
                'amount' => $request["amountPaid"] - 50,
                'current_balance' => $amount["current_balance"],
                'prev_balance' => $amount["prev_balance"]
            ]);

            // Transaction::where('ref', $request['transactionReference'])->update([
            //     "status" => "success"
            // ]);

            $transaction =  (new Transaction)->transaction(
                ''.$user,
                ''.$request['transactionReference'],
                ''.$request["amountPaid"],
                ''.$account->id,
                'Mavunifs',
                "success",
                "credit",
                'Wallet Topup',
                "Mavunifs wallet topup"
            );
            return response()->json('status', 201);
        }
    }
    
    public function webhookPaystack($request)
    {
        return \Payment::webhook($request);
    }
}
