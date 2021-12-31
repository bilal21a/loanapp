<?php
namespace App\Repositories;

use App\Events\KycEvent;
use App\Jobs\SignupJob;
use App\Profile;
use App\AccountCategory;
use App\Services\AccountService;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\NextOfKinResource;
use Illuminate\Support\Facades\Hash;
use App\NextOfKin;
use App\Kyc;



class ProfileRepository {

    protected $profile;
    protected $accountservice;

    public function __construct(Profile $profile, AccountCategory $category, AccountService $accountservice) {
        $this->profile = $profile;
        $this->accountservice = $accountservice;
        $this->category = $category;
    }

    public function index() {
        $data = $this->profile->where("is_active", 1)->take(100)->paginate(50);
        return ProfileResource::collection($data);
    }

    public function create($request) {

      if($this->profile->where("email", $request->email)->first()) {
        return response()->json(["status" => false, "message" => "An account already exists"]);
      }
        $tokenResponse = \Monnify::getAuthToken();
        if(array_key_exists("status", $tokenResponse) && !$tokenResponse['status']) {
            return json_encode($tokenResponse);
        }

       // return response()->json($tokenResponse);

        $accountRequirement = array(
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "access_token" => $tokenResponse["data"],
        );

        $response = \Monnify::reserveAccount($accountRequirement);

       if(array_key_exists("error", $response)) {
            return $response;
        }

       if(array_key_exists("responseBody", $response) && $response["responseMessage"] !== "success") {
            return response()->json($response["responseMessage"]);
        }

        $newProfile = $this->profile->create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "gender" => $request->gender,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone_number" => $request->phone_number,
            "city" => $request->city,
            "is_active" => 1,
            "user_type" => $request->user_type,
        ]);

        if($newProfile) {
            \DB::table("interest")->insert(["user_profile_id" => $newProfile->id,"value" => 0.00]);
            \DB::table("kyc")->insert(["user_profile_id" => $newProfile->id]);
            \DB::table("kyc")->insert(["user_profile_id" => $newProfile->id]);
            foreach($this->category->defaultAccounts() as $data) {
                foreach($data->subCategories as $account) {
                    $accountData = (object) array(
                        "uid" => $newProfile->id,
                        "category_id" => $account->id,
                        "account_number" => $response["responseBody"]["accountNumber"],
                        "account_ref" => $response["responseBody"]["accountReference"],
                        //"bank" => $response["responseBody"]["bankName"],
                        "bank" => "Mavunif Wallet",
                        "bankCode" => $response["responseBody"]["bankCode"],
                        "currency" => $response["responseBody"]["currencyCode"],
                    );
                    $this->accountservice->store($accountData);
                }
            }
            $details = [
                'first_name' => $newProfile->first_name,
                'type' => 'submission',
                'email' => $newProfile->email
            ];
            \dispatch(new SignupJob($details));
            return response()->json(["status" => true, "user" => new ProfileResource($newProfile)]);
        } else {
            return response()->json(["status" => false, "message" => "An error occurred while trying to create your profile. Try again"]);
        }

        return response()->json(["status" => false, "message" => "A network error occured. Try again"]);
    }

    public function getProfile($id) {

        $data = $this->profile->find($id);
        if($data) {
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new ProfileResource($data)
            ]);
        }

        return response()->json([
          "status" => false,
          "message" => "No record found",
          "data" =>  null
        ]);
    }

    public function update($id, array $request) {
        $found = $this->profile->find($id);

        if($found) {
            $found->update($request);
            $found->kyc->update($request);
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new ProfileResource($found)
            ]);
        }

        return response()->json([
          "status"  => false,
          "message" => "No matching profile found.",
          "data" => null
        ]);
    }

    public function delete($id) {
        $found = $this->profile->find($id);

        if($found) {
            $found->delete();
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new ProfileResource($found)
            ]);
        }
        return response()->json([
          "status" => false,
          "message" => "Error deleting profile.",
          "data" => null
        ]);
    }

    public function nextOfKin($request) {
      //return $request->all();
       // $user = auth()->guard('profile')->user();
        $found = NextOfKin::where("user_profile_id", $request->userid)->first();
        $kyc = Kyc::where("user_profile_id", $request->userid)->first();

        if($found) {

            $found->update([
                "name" => $request->name,
                "relationship" => $request->relationship,
                "phoneNumber" => $request->phoneNumber,
                "email" => $request->email
            ]);

            return response()->json([
              "status" => true,
              "message" => "Next of kin updated",
              "data" => new NextOfKinResource($found)
            ]);
        }
        else {

           $create = NextOfKin::create([
               "user_profile_id" => $request->userid,
                "name" => $request->name,
                "relationship" => $request->relationship,
                "phoneNumber" => $request->phoneNumber,
                "email" => $request->email
            ]);
            if($create) {
                if(!empty($kyc)) {
                    $kyc->update(["points" => 10]);
                }
                return response()->json([
                    "status" => true,
                    "message" => "Next of kin updated",
                ]);
            }
        }
        return response()->json([
          "status" => false,
          "message" => "Cannot perform operation.",
        ]);
    }

    public function updateKyc($id) {
        $found = $this->userkyc->find($id);

        $action = request()->query('action') ? request()->query('action') : ' ';
        $reason = request()->query('reason') ? request()->query('reason') : ' ';

        if($found && !empty($action)) {
            $status = $action === 'approve' ? true : false;
            $message =  !empty($reason) ? $reason : NULL;
            $points = $action === 'approve' ? 10 : 0;

            $found->update(['status' => $status, 'approval_status' => $status, 'reason_for_disapproval' => $message, 'points' => $points]);
            $details = array(
                'profile' => $found->profile,
                'kyc' => $found,
                'status' => $action.'d',
            );
            event( new KycEvent($details));
            return back()->withMessage("SAS ".\ucwords($action).'ed');
        }

        return back()->withErrors("No matching record found.");
    }
}
