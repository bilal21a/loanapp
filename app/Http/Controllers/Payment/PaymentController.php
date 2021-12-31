<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FundService;
use App\Transaction;
use App\Account;
use App\Profile;

class PaymentController extends Controller
{
    public $paymentservice;

    public function __construct(FundService $paymentservice)
    {
        $this->paymentservice = $paymentservice;
        //  $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index()
    {
        return $this->paymentservice->index();
    }

    public function banks()
    {
        return $this->paymentservice->banks();
    }

    public function verifyAccount(Request $request)
    {
        return $this->paymentservice->verifyAccount($request);
    }

    public function addCard(Request $request)
    {
        return $this->paymentservice->addCard($request);
    }

    public function verifyCard(Request $data)
    {
        return $this->paymentservice->verifyCard($data);
    }

    public function quickSave(Request $request)
    {
        return $this->paymentservice->quickSave($request);
    }

    public function autoSave(Request $request)
    {
        return $this->paymentservice->autoSave($request);
    }

    public function bankTransfer(Request $request)
    {
        return  $this->paymentservice->bankTransfer($request);
    }

    public function webhook(Request $request)
    {
        return  $this->paymentservice->webhook($request);
    }

    public function directTransfer(Request $request)
    {
        $transaction_keys = config('monnify.secretKey').'|'.$request['paymentReference'].'|'.$request['amountPaid'].'|'.$request['paidOn'].'|'.$request['transactionReference'];
        $transaction_hash = hash('SHA512', $transaction_keys);

        if ($transaction_hash === $request['transactionHash']) {

            // $fee = (1.5 / 100) * $request['amountPaid'];
            // $charge = $fee >= 2300 ? 2300 : $fee;

            $account = Account::where('account_ref', '=', $request['product']['reference'])->first();
            $user = Profile::find($account->user_profile_id);
            $account->update([
                'amount' => $request['amountPaid'],
                'current_balance' => $account->current_balance + $request['amountPaid'],
                'prev_balance' => $account->current_balance
            ]);

            Transaction::create([
                $user->id,
                $request['transactionReference'],
                $request["amountPaid"],
                $account->id,
                "Rulpay",
                "success",
                "credit",
                "Wallet Topup",
                "Direct Transfer"
            ]);

            return response()->json('status', 201);
        }
    }

    public function shagoWebhook(Request $request)
    {

        //   $all = $request->all();
        //   \Log::info($all);

        if ($request['status'] == 200) {
            if ($request['transId'] !== " ") {
                Transaction::where('ref', $request['requestId'])
                  ->update([
                    'status' => 'success',
                    'description' => "Meter Topup, token - ".$request['token'],
                ]);
            }
            return response()->json('status', 201);
        }
    }
}
