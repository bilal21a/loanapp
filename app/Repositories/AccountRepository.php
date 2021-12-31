<?php
namespace App\Repositories;

use App\Profile;
use App\Account;
use App\Card;
use App\Bank;
use App\Savings;
use App\Transaction;
use App\AutoSaving;
use App\Services\CardService;
use App\Http\Resources\AccountResource;
use App\Jobs\TransactionJob;
use App\Withdrawal;
use Carbon\Carbon;
use App\Jobs\QuickSaveJob;
use App\Jobs\WithdrawalJob;
use App\Http\Resources\ProfileResource;

class AccountRepository
{
    protected $account;
    protected $savings;
    protected $profile;
    protected $autosave;
    protected $bank;
    protected $cardservice;

    public function __construct(
        Account $account,
        Savings $savings,
        Profile $profile,
        AutoSaving $autosave,
        Bank $bank,
        CardService $cardservice
    ) {
        $this->account = $account;
        $this->savings = $savings;
        $this->profile = $profile;
        $this->autosave = $autosave;
        $this->bank = $bank;
        $this->cardservice = $cardservice;
    }

    public function index()
    {
        $data = $this->account->orderBy("created_at", "desc")->take(100)->paginate(50);
        //  return AccountResource::collection($data);
        return response()->json([
          "status" => true,
          "message" => "success",
          "data" => new AccountResource($data)
        ]);
    }

    public function create($request)
    {
        $creatAccount = $this->account->create([
            "user_profile_id" => $request->uid,
            "account_category_id" => $request->category_id,
            "account_number" => $request->account_number,
            "account_ref" => $request->account_ref,
            "bank_name" => $request->bank,
            "bank_code" => $request->bankCode,
            "currency" => $request->currency,
            "amount" => 0.00,
            "current_balance" => 0.00,
            "prev_balance" => 0.00,
            "status" => 1,
        ]);
    }

    public function getAccount($id)
    {
        $account = $this->account->find($id);
        if ($account) {
            //  return new AccountResource($account);
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new AccountResource($account)
            ]);
        }

        return response()->json(["status" => false, "message" => "Account not found"]);
    }

    public function update($id, $request)
    {
        $account = $this->account->find($id);

        if ($account) {
            $this->account->update([
                "user_profile_id" => $request->uid,
                "account_category_id" => $request->categoryId,
                "amount" => $request->amount,
                "current_balance" => $account->current_balance,
                "prev_balance" => $account->prev_balance,
                "status" => 1,
            ]);
            //  return new AccountResource($account);
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new AccountResource($account)
            ]);
        }

        return response()->json(["status" => false, "message" => "Account not account."]);
    }

    public function delete($id)
    {
        $account = $this->account->find($id);
        if ($account) {
            $account->delete();
        }
        return response()->json(["status" => false, "message" => "Error deleting account."]);
    }

    public function quickSave($request)
    {

        //$uid = auth()->guard("profile")->user()->id;
        $user = auth()->guard("profile")->user();
        $account = $this->account->where("user_profile_id", $user->id)->first();
        $cardDetails = Card::where("user_profile_id", $user->id)->where('is_default', true)->first();
        $reference = $this->refCode();
        $fee = 0;

        if ($request->has("payment_method") && $request->payment_method === "transfer") {
            $charge = $request->amount / 100;
            return response()->json([
                "status" => true,
                "action" => "fee",
                "data" => $charge, "message" => "You will be charged ₦50 for this transaction"
            ]);
        }

        if ($request->has("payment_method") && $request->payment_method === "wallet") {
            if($account->current_balance <= $request->amount) {
                return response()->json([
                    "status" => false,
                    "data" => [],
                    "message" => "Insufficient fund"
                ]);
            }

           $savingWallet = Account::where("user_profile_id", $user->id)->where('id', $request->account_id)->first();
            //\Log::info("===>", (array) $savingWallet);
            $savingWallet->update([
                "amount" => $request->amount,
                "current_balance" => $savingWallet->current_balance + $request->amount,
                "prev_balance" => $savingWallet->current_balance
            ]);
            $account->update([
                "amount" => $request->amount,
                "current_balance" => $account->current_balance - $request->amount,
                "prev_balance" => $account->current_balance
            ]);
             Transaction::create([
                "user_profile_id" => $user->id,
                "ref" => $reference,
                "account_id" => $account->id,
                "amount" => $request->amount,
                "type" => "credit",
                "sub_type" => "Quick save",
                "beneficiary" => $user->first_name.' '.$user->last_name,
                "vendor" => "Mavunifs",
                "description" => "Wallet to wallet topup",
                "status" => "success",
             ]);
            return response()->json([
                "status" => true,
                "data" => new ProfileResource($user),
                "message" => "Wallet topup successful"
            ]);
        }

        if ($request->has("get_fee") && $request->get_fee === true) {
            $fee = $request->amount < 2500 ? ((1.5 / 100) * $request->amount) : (((1.5 / 100) * $request->amount)) + 100 ;
            return response()->json([
                "status" => true,
                "action" => "fee",
                "data" => $fee,
                "message" => "You will be charged ₦".$fee." for this transaction"
            ]);
        }


        if (!$cardDetails) {
            return response()->json([
                "status" => false,
                "message" => "Please set a default payment card"
            ]);
        }



        if (!$cardDetails) {
            return response()->json(["status" => false, "message" => "No payment card found"]);
        }

        $acountDetails = (object) array(
            "auth_code" => $cardDetails->auth_code,
            "email" => $cardDetails['user']["email"],
            "amount" => $request->amount + $fee,
            "reference" => $reference
        );
        $data = $this->account->deposit(
            trim($request->amount + $fee),
            $account->current_balance,
            $account->prev_balance
        );

        if ($account) {
            $transaction = Transaction::create([
                "user_profile_id" => $user->id,
                "ref" => $reference,
                "account_id" => $account->id,
                "amount" => $request->amount,
                "type" => "credit",
                "sub_type" => "Quick save",
                "beneficiary" => $user->first_name.' '.$user->last_name,
                "vendor" => "Mavunifs",
                "description" => "Wallet topup",
                "status" => "pending",
            ]);
            $response = \Payment::chargeAuthorization($acountDetails);
            if (!$response["status"]) {
                $transaction->update(["status" => "failed"]);
                return response()->json(["status" => false, "message" => "An API error occured"]);
            }

            if ($response["status"] && $response["data"]["status"] !== "success") {
                $transaction->update(["status" => "failed"]);
                return response()->json([
                    "message" => $response["data"]["gateway_response"],
                    "status" => false, ]);
            }

            $account->update([
                "amount" => $data["deposit_amount"],
                "current_balance" => $data["current_balance"],
                "prev_balance" => $data["prev_balance"],
            ]);
            $transaction->update(["status" => "success"]);
            \dispatch(new QuickSaveJob(auth()->guard("profile")->user(), $request->amount));

            $msg = ["status" => true, "message" => "Wallet topup successful", "amount" => $data["deposit_amount"], "user" => new ProfileResource($user)];
        //  $msg = ["message" => false, "message" => "Could not create transaction record" ];
        } else {
            $msg = ["status" => false, "message" => "No matching account found."];
        }
        return response()->json($msg);
    }

    public function withdraw($request)
    {
        $account = $this->account->with("user")->with("withdrawalSettings")
                        ->with("accountType")->first();
        $today = Carbon::now()->toDateString();
        $fee = 0;
        $user = auth()->guard("profile")->user();

        if ($account->withdrawalSettings->count() < 1) {
            return response()->json(["status" => false, "message" => "No withdrawal setting found"]);
        }

        $withdrawal_day = Carbon::parse($account->withdrawalSettings[0]["withdrawal_day"])->toDateString();

        if ($withdrawal_day !== $today) {
            return response()->json(["message" => "You cannot make withdrawal till"." ".$withdrawal_day, "status" => false]);
        }

        if ($request->has("get_fee") && $request->get_fee === true) {
            $fee = $request->amount < 2500 ? (1.5 / 100) * $request->amount : ((1.5 / 100) * $request->amount) + 100 ;
            return response()->json(["status" => true, "action" => "fee", "data" => $fee, "message" => "You will be charged ".$fee." for this transaction"]);
        }

        if (($request->amount + $fee) >= $account->current_balance) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
        }

        if ($request->amount < 50) {
            return response()->json(["status" => false, "message" =>  "Minimum withdrawal is 50"]);
        }

        if ($request->amount >= 100001) {
            return response()->json(["status" => false, "message" =>  "Maximum withdrawal at a time is 100,000"]);
        }

        $bank = $this->bank->where("user_profile_id", $account->user->id)->first();

//        $withrawalData = (object) array(
//            "amount" => $request->amount,
//            "note" => $request->note,
//            "recipient_code" => $bank->recipient_code,
//        );
        // $response = \Payment::transfer($withrawalData);
        $response = \Monnify::bankTransfer($request);

        if (!$response["status"]) {
            return response()->json($response);
        }

        $data = $this->account->withdrawal(
            trim($request->amount),
            $account->current_balance,
            $account->prev_balance
        );

        if ($account) {
            $account->update([
                "amount" => $data["withdrawal_amount"],
                "current_balance" => $data["current_balance"],
                "prev_balance" => $data["prev_balance"],
            ]);
            dispatch(new WithdrawalJob(auth()->guard("profile")->user(), $request->amount))
                        ->delay(Carbon::now()->addMinute(5));

            // $this->savings->history(auth()->guard("profile")->user()->id, $account->id, $data[""], "Withdrawal")
            $transaction =  (new Transaction)->transaction(
                auth()->guard("profile")->user(),
                $this->refCode(),
                $data["withdrawal_amount"],
                $account->id,
                "Kredda",
                "success",
                "debit",
                "Withdrawal",
                "Withdrawal"
            );

            $msg = [
                "status" => true,
                "message" => "Withdrawal uccess full",
                "user" => new ProfileResource(auth()->guard("profile")->user()),
                "amount" => $data["withdrawal_amount"],
                "code" => 200
            ];
        } else {
            $msg = ["message" => "No matching account found", "status" => false, "code" => 404];
        }

        return response()->json($msg);
    }

    public function autoSaveConfig($request)
    {
        return $this->autosave->create([
            "user_profile_id" => auth()->guard("profile")->user()->id,
            "prefered_date" => trim($request->prefered_date),
            "prefered_time" => trim($request->prefered_time),
            "amount" => trim($request->amount),
        ]);
    }

    public function updateSettings($id, array $request)
    {
        $autosave = $this->autosave->find($id);
        if ($autosave) {
            $autosave->update($request);
        }
        return response()->json(["status" => false, "message" => "Not found"]);
    }


    public function addCard($request)
    {
        $card = (object) array(
          "email" => auth()->guard("profile")->user()->email,
          "amount" => 50,
          "desc" => "Card verification",
          "cardcvv"    => $request->cardcvv,
          "cardNumber"    => $request->cardNumber,
          "expiryMonth" => $request->expiryMonth,
          "expiryYear" => $request->expiryYear,
          "cardPin" => $request->cardPin,
          "cardPin" => $request->cardPin,
        );

        if ($request->has("otp") && !empty($request->otp) && $request->has('reference') && !empty($request->reference)) {
            $response = \Payment::sendOtp($request);
        } elseif ($request->has("phone_number") && !empty($request->phone_number) && $request->has('reference') && !empty($request->reference)) {
            $response = \Payment::sendPhone($request);
        } elseif ($request->has("action") && $request->action === 'requery') {
            $response = \Payment::verifyTransaction($request);
        } else {
            $response = \Payment::charge($card);
        }

        //\Log::info((array) $response);


        if (!isset($response["status"]) || !$response["status"]) {
            return response()->json(["status" => false, "message" => $response["message"]]);
        }

        if ($response["status"] && $response["data"]["status"] === "send_phone") {
            return \response()->json([
               "status" => true,
               "type" => "phone",
               "message" => $response["data"]["display_text"],
               "reference" => $response["data"]["reference"]
            ]);
        }

        if ($response["status"] && $response["data"]["status"] === "send_otp") {
            return \response()->json([
               "status" => true,
               "type" => "otp",
               "message" => $response["data"]["display_text"],
               "reference" => $response["data"]["reference"]
            ]);
        }

        if ($response["status"] && $response["data"]["status"] === "open_url") {
            return \response()->json([
               "status" => true,
               "type" => "url",
               "message" => $response["message"],
               "reference" => $response["data"]["reference"],
               "url" => $response["data"]["url"],
            ]);
        }

        if ($response["status"] && $response["data"]["status"] === "success" && isset($response["data"]["authorization"]["reusable"])) {
            $user = $this->profile->where("email", $response["data"]["customer"]["email"])->first();
            $account = Account::where('user_profile_id', $user->id)->first();

            $transaction = Transaction::create([
                "user_profile_id" => $user->id,
                "ref" => $response["data"]["reference"],
                "account_id" => $account->id,
                "amount" => ($response["data"]["amount"] / 100),
                "type" => "credit",
                "sub_type" => "card",
                "beneficiary" => $user->first_name.' '.$user->last_name,
                "vendor" => "Mavunifs",
                "description" => "Debit card verification",
                "status" => "pending",
            ]);

            $cardData = (object) array(
            "uid" => $user->id,
            "authCode" => $response["data"]["authorization"]["authorization_code"],
            "cardType" => $response["data"]["authorization"]["card_type"],
            "bank" => $response["data"]['authorization']["bank"],
            "countryCode" => $response["data"]['authorization']["country_code"],
            "number" => $response["data"]['authorization']["last4"],
            "expYear" => $response["data"]['authorization']["exp_year"],
            "expMonth" => $response["data"]['authorization']["exp_month"],
        );

            $this->cardservice->newCard($cardData);

            $creditAmount = $response["data"]["amount"] / 100;
            $account->update([
            "amount" => $creditAmount,
            "current_balance" => $account->current_balance + $creditAmount,
            "prev_balance" => $account->current_balance
        ]);

            $transaction->update(["status" => "success"]);
            $details = (object) array(
          "type" => "credit",
          "email" => $user->email,
          "name" => $user->last_name,
          "amount" => 50,
          "service" => $response["data"]["authorization"]["card_type"]." card verification",
          "status" => "success",
          "reference" => $this->refCode()
        );
            \dispatch(new TransactionJob($user->email, $details));

            return response()->json([
            "status" => true,
            "message" => "success",
            "type" => "complete",
            "data" => new ProfileResource($user) //$this->cardservice->newCard($cardData)
        ]);
        } else {
            return response()->json(["message" => $response["data"]["message"], "status" => false]);
        }
    }



    public function refCode()
    {
        return bin2hex(random_bytes(5));
    }

    public function walletTopup($request) {

        $user = auth()->guard("profile")->user();
        $account = $this->account->find($request->account_id);
        $cardDetails = Card::find($request->card);
        $reference = $this->refCode();

        if(!$cardDetails) {
            return response()->json([
                "status" => false,
                "type" => "card",
                "message" => "No default card found",
                "action" => "card"
            ]);
        }

        $acountDetails = (object) array(
            "auth_code" => $cardDetails->auth_code,
            "email" => $cardDetails['user']["email"],
            "amount" => $request->amount,
            "reference" => $reference
        );

        $data = $this->account->deposit(
            trim($request->amount),
            $account->current_balance,
            $account->prev_balance
        );

        if($account) {
            $transaction = Transaction::create([
                "user_profile_id" => $user->id,
                "ref" => $reference,
                "account_id" => $account->id,
                "amount" => $request->amount,
                "type" => "credit",
                "sub_type" => "wallet",
                "beneficiary" => $user->first_name.' '.$user->last_name,
                "vendor" => "BillsPadi",
                "description" => "Wallet topup",
                "status" => "pending",
            ]);

            $response = \Payment::chargeAuthorization($acountDetails);
            if(!$response["status"]) {
                $transaction->update(["status" => "failed"]);
                //\Log::info((array) $response);
                return response()->json(["status" => false, "message" => "An API error occured"]);
            }

            if($response["status"] && $response["data"]["status"] !== "success") {
                $transaction->update(["status" => "failed"]);
                return response()->json([
                    "message" => $response["data"]["gateway_response"],
                    "status" => false,
                ]);
            }

            $account->update([
                "amount" => $data["deposit_amount"],
                "current_balance" => $data["current_balance"],
                "prev_balance" => $data["prev_balance"],
            ]);
            $transaction->update(["status" => "success"]);
            Notification::create([
                "type" => "wallet topup",
                "message" => "Your wallet has been credited with N".$request->amount,
                "user_profile_id" => $user->id,
                "status" => 1
            ]);
            dispatch(new QuickSaveJob(auth()->guard("profile")->user(), $request->amount));

            $msg = ["status" => true, "amount" => $data["deposit_amount"], "user" => new ProfileResource($user), "message" => "Wallet Topup Successful"];
            //  $msg = ["message" => false, "message" => "Could not create transaction record" ];

        } else {
            $msg = ["status" => false, "message" => "No matching account found."];
        }

        return response()->json($msg);
    }

    public function withdrawToWallet($request) {

        $user = auth()->guard("profile")->user();
        $debit_account = $this->account->find($request->account_id);
        $credit_account = $this->account->where("user_profile_id", $user->id)->first();
        $withdrawal = Withdrawal::where('user_profile_id', $user->id)->first();
        //$withdrawal_day = Carbon::parse($withdrawal->withdrawal_day)->toDateString();
        $today = Carbon::now()->toDateString();
        $reference = $this->refCode();

        if($debit_account) {

            if($debit_account->current_balance <= $request->amount) {
                return response()->json(["status" => false, "message" => "Insufficient funds", "action" => "wallet"]);
            }

            if(!$withdrawal) {
                Withdrawal::create([
                    "user_profile_id" => $user->id,
                    "user_account_id" => $debit_account->id,
                    "withdrawal_day" => $today,
                    "last_withdrawal_day" => $today,
                    "status" =>  1
                ]);
            }
            else {
                $withdrawal_day = Carbon::parse($withdrawal->withdrawal_day)->toDateString();
                if($withdrawal_day !== $today) {
                    return response()->json([
                        "message" => "Next withdrawal day is ".$withdrawal->withdrawal_day,
                        "status" => false,
                    ]);
                }
            }

            $debit_account->update([
                "amount" => $request->amount,
                "current_balance" => $debit_account->current_balance - $request->amount,
                "prev_balance" => $debit_account->current_balance,
            ]);

            $credit_account->update([
                "amount" => $request->amount,
                "current_balance" => $credit_account->current_balance + $request->amount,
                "prev_balance" => $credit_account->current_balance,
            ]);
            $withdrawal->update([
                "last_withdrawal_day" => $withdrawal->withdrawal_day,
                "withdrawal_day" => Carbon::now()->addDay(30)
            ]);

//            Notification::create([
//                "type" => "withdrawal",
//                "message" => "Your wallet has been credited with N".$request->amount,
//                "user_profile_id" => $user->id,
//                "status" => 1
//            ]);

            $transaction = Transaction::create([
                "user_profile_id" => $user->id,
                "ref" => $reference,
                "account_id" => $debit_account->id,
                "amount" => $request->amount,
                "type" => "credit",
                "sub_type" => "wallet",
                "beneficiary" => $user->first_name.' '.$user->last_name,
                "vendor" => "BillsPadi",
                "description" => "withdrawal",
                "status" => "success",
            ]);

            $details = (object) array(
                "type" => "debit",
                "email" => $user->email,
                "name" => $user->last_name,
                "amount" => $request->amount,
                "service" => "Savings withdrawal",
                "status" => "success",
                "reference" => \Monnify::refCode()
            );

            $detail = (object) array(
                "type" => "credit",
                "email" => $user->email,
                "name" => $user->last_name,
                "amount" => $request->amount,
                "service" => "Wallet topup",
                "status" => "success",
                "reference" => \Monnify::refCode()
            );

            dispatch(new TransactionJob($user->email, $details));
            dispatch(new TransactionJob($user->email, $detail));


            $msg = ["status" => true, "user" => new ProfileResource($user), "message" => "Withdrawal Successful"];

        } else {
            $msg = ["status" => false, "message" => "No matching account found."];
        }

        return response()->json($msg);
    }



}
