<?php 

namespace App;
    
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
    
class Shago {
  public function storepu(Request $request)
  {
    $bal = \DB::table('users')
              ->select('balance')
              ->where('email', auth()->user()->email)
              ->sum('balance');

    if ($request->amount < $bal )
    {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://shagopayments.com/api/live/b2b",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
          'serviceCode' => 'AOB',
          'disco' => $request->product_name,
          'meterNo' => $request->meter_no,
          'type' => $request->meter_type,
          'phone' => $request->phone,
          'email' => $request->email,
          'amount' => $request->amount
        ]),
        CURLOPT_HTTPHEADER => [
          "Content-Type: application/json",
          "X-Request-Hash: 6383d558cfccae9b1a5837ff6610b350751c515fab4b1dd84d7e78f5d3c55d39"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        // there was an error contacting the API
        die('Curl returned error: ' . $err);
      }
      elseif ($response) {
            Purchase::create([
              'userid' => auth()->user()->id,
              'product_name' => $request->product_name,
              'transid' => $request->transid,
              'account_no' => $request->account_no,
              'account_name' => auth()->user()->first_name . " " . auth()->user()->last_name,
              'meter_type' => $request->meter_type,
              'meter_no' => $request->meter_no,
              'phone' => $request->phone,
              'email' => $request->email,
              'amount' => $request->amount,
              'payment_status' => 'success',
          ]);

          $total  = $bal - $request->amount;

          \DB::table('users')
                    ->select('balance')
                    ->where('email', auth()->user()->email)
                    ->update([
                      'balance' => $total,
                    ]);

          Alert::toast('Great! Purchase Completed ', 'success');
          return redirect()->back();
      }
      else {
        die("Connection Failure");
      }
    }
    else {
        Purchase::create([
            'userid' => auth()->user()->id,
            'product_name' => $request->product_name,
            'transid' => $request->transid,
            'account_no' => $request->account_no,
            'account_name' => auth()->user()->first_name . " " . auth()->user()->last_name,
            'meter_type' => $request->meter_type,
            'meter_no' => $request->meter_no,
            'phone' => $request->phone,
            'email' => $request->email,
            'amount' => $request->amount,
            'payment_status' => 'failed',
        ]);

        Alert::toast('Failed! Wallet balanace is low,  Please topup ', 'error');
        return redirect()->back();
    }

  }
}