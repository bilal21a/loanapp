<?php
  namespace App\Services;

  use Twilio\Rest\Client;

  class SMSService {
      
    public function BulkSMSNg($to, $body) {
      $response = \Http::post('https://www.bulksmsnigeria.com/api/v1/sms/create', [
        'api_token' => env('SMS_API_TOKEN'),
        'from' => 'Mavunifs',
        'to' => $to,
        'body' => $body,
        'dnd' => 3
      ]);
      
      $res = json_decode($response, true);
      
    //   \Log::info((array) $response->body());
      
      if(isset($response["error"]) || (isset($response["data"]) && $response["data"]["status"] !== "success") ) {
          $phoneNumber = "+234".$to;
          $this->twilioSms($body, $phoneNumber);
      }
      
    }
    
    public function twilioSms($msg, $phoneNumber) {
      $client = new Client(config("settings.twilio_sid"), config("settings.twilio_token"));
      
     $that = $client->messages->create(
          $phoneNumber,
          [
              "from" => config("settings.twilio_from"),
              "body" => $msg
          ]);
     
    // \Log::info($that);

      
    }

  }
