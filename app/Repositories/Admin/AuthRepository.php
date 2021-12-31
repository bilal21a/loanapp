<?php 
namespace App\Repositories\Admin;

use App\Notifications\ForgotPasswordNotification;
use App\Admin;
use Hash;

class AuthRepository {

    protected $admin;

    public function __construct(Admin $admin) {
        $this->admin = $admin;
    }

    public function login($request) {

        //$credentials = $request->only("email", "password", $is_active );
        $credentials = array("email" => $request->email, "password" => $request->password, "is_active" => 1);

        if(auth()->guard("admin")->attempt($credentials)) {
            return redirect()->intended(route("dashboard"));
        }

        return back()->withErrors("Invalid email or password.");
    }

    public function phone_checker($data) {
        $found = $this->profile->where("phone_number", $data->phonenumber)->first();

        if($found && $found->isVerified) {
            return response()->json([
                "found" => true, 
                "isVerified" => true,
                "user" => new ProfileResource($found),
            ]);
        }

        if($found && !$found->isVerified) {
            return response()->json(["found" => true, "isVerified" => false]);
        }

        return response()->json(["found" => false, "isVerified" => false]);
    }

    public function sendOTP($request) {
        $otp = rand(100000, 999999);

        if($request->has("phonenumber") && $request->phonenumber != "") {
            session()->put("OTP", $otp);
            return response()->json(["status" => true, "type" => "phonen number", "otp" => $otp]); 
        } 
        
        if($request->has("bvn") && $request->bvn !== "") {
            session()->put("OTP", $otp);    
            return response()->json(["status" => true, "type" => "bvn", "otp" => $otp]); 
        }

        return response()->json(["status" => false, "message" => "phonenumber or bvn parameter missing"]);
    }

    public function verify_OTP($data) {

        if($data->has("phonenumber")) {

            $otp = session()->get("OTP");

            if($otp === $data->otp) {
                $profile = $this->profile->where("phone_number", $data->phonenumber)->first();
                $profile->update([
                    "isVerified" => 1,
                ]);
                \Session::forget("OTP");
                return response()->json(["message" => true]);
            }
            return response()->json(["message" => false]);
        }

        if($data->has("bvn")) {

            $otp = session()->get("OTP");

            if($otp === $data->otp) {
                \Session::forget("OTP");
                return response()->json(["message" => "success", "type" => "bvn", "status" => true]);
            }

            return response()->json(["message" => "failed", "type" => "bvn", "status" => false]);
        }

        return response()->json([
            "status" => false, 
            "message" => "Error! Send request with the appropriate parameter"
        ]);
    }

    public function confirmPassword($data) {
        $user = $this->profile->where("email", $data->email)->first();

        if(Hash::check($data->password, $user->password)) {
            return new ProfileResource($user);
        }
        else {
            return response()->json(["message" => "No matching password", "status" => false]);
        }
    }

    public function forgotPassword($request) {
        $validator = \Validator::make($request->all(), [
            "email" => "required|email",
        ]);

        if($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }

        $found = $this->profile->where("email", $request->email)->count();

        if($found == 1) {
            $otp = rand(100000, 999999);
            $user = auth()->guard("profile")->user();
            $user->notify(new ForgotPasswordNotification($otp));
            return response()->json(array("status" => true, "message" => "Email exists"));
        }
        else {
            return response()->json(array("status" => false, "message" => "Not found", "code" => 404));
        }
    }

    public function resetPassword($request) {

        $validator = \Validator::make($request->all(), [
            "old_password" => "required|min:6",
            "password" => "required|min:6|confirmed",
        ]);

        if($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }
        $user = $this->profile->findOrFail(auth()->guard("profile")->user()->id);

        if(\Hash::check($request->old_password, $user->password)) {
            
            $resetPassword = $user->update([
                "password" => \Hash::make($request->password)
                ]);
    
            if($resetPassword) {
                return response()->json(array("status" => true, "message" => "Password reset successfull"));
            } 
            else {
                return response()->json(array("status" => false, "message" => "Error reseting password"));
            }
        }

        return response()->json(array("status" => false, "message" => "Old password doesn't match"));

    }
}