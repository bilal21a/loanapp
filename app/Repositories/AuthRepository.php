<?php
namespace App\Repositories;

use App\Http\Resources\ProfileResource;
use App\Notifications\ForgotPasswordNotification;
use App\Profile;
use Hash;
use App\Services\SMSService;
use Twilio\Rest\Client;
use App\Monnify;
use App\Account;

class AuthRepository
{
    protected $profile;
    protected $sms;

    public function __construct(Profile $profile, SMSService $sms)
    {
        $this->profile = $profile;
        $this->sms = $sms;
    }

    public function login($request)
    {
        $credentials = array("email" => $request->email, "password" => $request->password, "is_active" => 1);

        if (!$token = auth()->guard("profile")->attempt($credentials)) {
            return response()->json(["message" => "Invalid credentials", "status" => false]);
        }


        // $account = Account::where("user_profile_id", auth()->guard("profile")->user()->id)->first();
        // if($account->bank_name === "Providus Bank") {
        //     $respo = \Monnify::getAccountNumbers($account->account_ref);
        //     Account::where("account_ref", $account->account_ref)->update(["account_number" => $respo[0]["accountNumber"], "bank_name" => $respo[0]["bankName"], "bank_code" => $respo[0]["bankCode"]]);
        // }

        return $this->respondWithToken($token);
    }

    public function phone_checker($data)
    {
        $found = $this->profile->where("phone_number", $data->phonenumber)->first();
    \Log::info($found);
        if ($found && $found->isVerified) {
            return response()->json([
                "status" => true,
                "isVerified" => true,
                "user" => new ProfileResource($found),
            ]);
        }

        if ($found && !$found->isVerified) {
            return response()->json(["status" => true, "isVerified" => false, "user" => new ProfileResource($found)]);
        }

        return response()->json(["status" => false, "isVerified" => false]);
    }

    public function sendOTP($request)
    {
        $otp = rand(100000, 999999);
        if ($request->has("phonenumber") && $request->phonenumber != "") {
            $sendOtp = \DB::table("otp_verification")->insert(["otp_code" => $otp, "type" => "phone number", "status" => 1]);
            if ($sendOtp) {
                $msg = 'Your Mavunifs verification code is '.$otp;
                // $this->sms->BulkSMSNg($request->phonenumber, $msg);
                $phoneNumber = "+27".$request->phonenumber;
                // $this->sms->twilioSms($msg, $phoneNumber);
                return response()->json(["status" => true, "message" => "Otp sent", "type" => "phone number", "otp" => $otp]);
            }

            return response()->json(["status" => false, "message" => "Could not send OTP"]);
        }

        if ($request->has("bvn") && $request->bvn !== "") {
            $response = \Payment::verifyBVN($request->bvn);
            if (!$response["status"]) {
                return response()->json(["status" => false, "message" => "Invalid BVN code"]);
            }

            $sendOtp = true; /* Send otp to the registered BVN  >>>> $response["data"]["mobile"]; phone number*/

            if ($sendOtp) {
                $msg = 'Your Mavunifs verification code is '.$otp;
                $this->sms->BulkSMSNg($request->phonenumber, $msg);
                \DB::table("otp_verification")->insert(["otp_code" => $otp, "type" => "bvn", "status" => 1]);
                return response()->json(["status" => true, "type" => "bvn", "otp" => $otp]);
            }
            return response()->json(["status" => false, "message" => "OTP not sent"]);
        }

        return response()->json(["status" => false, "message" => "phonenumber or bvn parameter missing"]);
    }

    public function verify_OTP($data)
    {
        if ($data->has("phonenumber")) {
            $otp = \DB::table("otp_verification")->where("otp_code", $data->otp)->first();

            if ($otp) {
                $profile = $this->profile->where("phone_number", $data->phonenumber)->first();

                if ($profile) {
                    $profile->update([
                        "isVerified" => 1,
                    ]);
                    \DB::table("otp_verification")->where("id", $otp->id)->delete();
                    return response()->json(["status" => true, "message" => "success", "isUser" => true, "user" => $profile]);
                }
                \DB::table("otp_verification")->where("id", $otp->id)->delete();
                return response()->json(["status" => true, "isUser" => false, "message" => "success"]);
            }
            return response()->json(["status" => false, "message" => "Invalid code"]);
        }

        if ($data->has("bvn")) {
            $otp = \DB::table("otp_verification")->where("otp_code", $data->otp)->first();

            if ($otp) {
                \DB::table("otp_verification")->where("id", $otp->id)->delete();
                return response()->json(["message" => "success", "type" => "bvn", "status" => true]);
            }

            return response()->json(["message" => "Invalid code", "type" => "bvn", "status" => false]);
        }

        return response()->json([
            "status" => false,
            "message" => "Error! Send request with the appropriate parameter"
        ]);
    }

    public function confirmPassword($data)
    {
        $user = auth()->guard("profile")->user();

        if (Hash::check($data->password, $user->password)) {
            return  response()->json(['status' => true, 'data' => new ProfileResource($user)]);
        } else {
            return response()->json(["message" => "Invalid Password", "status" => false]);
        }
    }

    public function forgotPassword($request)
    {
        $validator = \Validator::make($request->all(), [
            "email" => "required|email",
        ]);

        if ($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }

        $found = $this->profile->where("email", $request->email)->count();

        if ($found == 1) {
            $otp = rand(100000, 999999);
            $user = auth()->guard("profile")->user();
            $user->notify(new ForgotPasswordNotification($otp));
            return response()->json(array("status" => true, "message" => "Password reset code sent."));
        } else {
            return response()->json(array("status" => false, "message" => "Email didn't match any record", "code" => 404));
        }
    }

    public function changePassword($request)
    {
        $validator = \Validator::make($request->all(), [
            "old_password" => "required|min:6",
            "password" => "required|min:6|confirmed",
            "password_confirmation" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }
        $user = auth()->guard("profile")->user();

        if (\Hash::check($request->old_password, $user->password)) {
            $resetPassword = $user->update([
                "password" => \Hash::make($request->password)
                ]);

            if ($resetPassword) {
                return response()->json(array("status" => true, "message" => "Password reset successfull"));
            } else {
                return response()->json(array("status" => false, "message" => "Error reseting password"));
            }
        }

        return response()->json(array("status" => false, "message" => "Old password doesn't match"));
    }


    public function resetPassword($request)
    {
        $validator = \Validator::make($request->all(), [
            "password" => "required|min:6|confirmed",
            "password_confirmation" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }
        $user = $this->profile->where('phone_number', $request->phone_number)->first(); //auth()->guard("profile")->user();

        $resetPassword = $user->update([
            "password" => \Hash::make($request->password)
            ]);
        if ($resetPassword) {
            return response()->json(array("status" => true, "message" => "Password reset successfull", "user" => $user));
        } else {
            return response()->json(array("status" => false, "message" => "Error reseting password"));
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
        'status' => true,
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->guard("profile")->factory()->getTTL() * 360,
        'user' => new ProfileResource(auth()->guard("profile")->user()),
      ]);
    }

    public function sendSms($msg, $phoneNumber)
    {
        $client = new Client(config("settings.twilio_sid"), config("settings.twilio_token"));

        $client->messages->create(
            $phoneNumber,
            [
              "from" => config("settings.twilio_from"),
              "body" => $msg
          ]
        );
    }
}
