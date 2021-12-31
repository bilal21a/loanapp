<?php
  namespace App\Monnify;

  class Webhook {
    public function webhook(Request $request) {
    
        $transaction_keys = config('settings.monnify_secret').'|'.$request['paymentReference'].'|'.$request['amountPaid'].'|'.$request['paidOn'].'|'.$request['transactionReference'];
        $transaction_hash = hash('SHA512', $transaction_keys);
    
        if($transaction_hash === $request['transactionHash']) {
    
            // $fee = (1.5 / 100) * $request['amountPaid'];
            // $charge = $fee >= 2300 ? 2300 : $fee;
    
            $account = \DB::table('user_account')
                ->where('account_ref', '=', $request['product']['reference'])
                ->first();
    
            $db = \DB::table('user_account')
                ->where('account_ref', '=', $request['product']['reference'])
                ->update([
                  'amount' => $request['amountPaid'],
                  'current_balance' => $account->current_balance + $request['amountPaid'],
                  'prev_balance' => $account->current_balance
            ]);
    
            $transaction =  (new Transaction)->transaction(
              $user,
              $request['transactionReference'],
              $discountedAmt,
              $account->id,
              $request->type,
              $transactionStatus,
              "credit",
              "Wallet top up",
              $request->type." data bunlde"
            );
    
            $postdepo = Deposit::create([
                'name' => $user->first_name.' '.$user->last_name,
                'email' => $user->email,
                'accountno' => $user->bank_account_no,
                'transid' => $request['transactionReference'],
                'amount' => $request['amountPaid'],
                'status' => 'success',
                'charge' => $charge,
                'gateway' => 'Monnify',
              ]);
    
            return response()->json('status', 201);
         }
    }
  }

