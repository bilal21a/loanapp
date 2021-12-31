<?php 

return [
  
  "shagoKey" => env("SHAGO_API_KEY"),

  "mobileng" => [
    "userid" => env("MOBILENG_API_USERID"),
    "password" => env("MOBILENG_API_PASS"),
    "url" => "",
  ],
  
  "twilio_sid" => env("TWILIO_SID"),
  "twilio_token" => env("TWILIO_TOKEN"),
  "twilio_from" => env("TWILIO_FROM")
];