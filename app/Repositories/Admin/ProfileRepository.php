<?php
namespace App\Repositories\Admin;

use App\EmploymentData;
use App\Events\KycEvent;
use App\Jobs\SasJob;
use App\NextOfKin;
use App\Profile;
use App\AccountCategory;
use App\Services\Admin\AccountService;
use App\Http\Resources\ProfileResource;
use App\SocialLink;
use Illuminate\Support\Facades\Hash;
use App\Kyc;
use App\UserKyc;


class ProfileRepository {

    protected $profile;
    protected $accountservice;
    protected  $kyc;
    protected  $social;
    protected  $employment;
    protected  $userkyc;

    public function __construct(
        Profile $profile, AccountCategory $category,
        AccountService $accountservice, Kyc $kyc,
        EmploymentData $employment,
        SocialLink $social,
        UserKyc $userkyc
    ) {
        $this->profile = $profile;
        $this->accountservice = $accountservice;
        $this->category = $category;
        $this->kyc = $kyc;
        $this->social = $social;
        $this->employment = $employment;
        $this->userkyc = $userkyc;
    }

    public function index() {
        $data = $this->profile->orderBy("last_name", "asc")->get();
        return view("website.admin.users.index")->withUsers(ProfileResource::collection($data));
    }

    public function agents() {
        $data = $this->profile->where('user_type', 'agent')->orderBy("last_name", "asc")->get();
        return view("website.admin.users.agents")->withUsers(ProfileResource::collection($data));
    }

    public function create($request) {
        return view("website.admin.users.new-user");
    }

    public function store($request) {
        $request = (object) $request;
      if($this->profile->where("email", $request->email)->first()) {
        return back()->withErrors("An account already exists");
      }

       $tokenResponse = \Monnify::getAuthToken();

      if(!$tokenResponse["status"]) {
        return back()->withErrors($tokenResponse);
      }

      $accountRequirement = array(
        "first_name" => $request->first_name,
        "last_name" => $request->last_name,
        "email" => $request->email,
        "access_token" => $tokenResponse["data"],
      );

       $response = \Monnify::reserveAccount($accountRequirement);

       if(array_key_exists("error", $response)) {
            return back()->withErrors($response["error"]);
        }

       if(array_key_exists("responseBody", $response) && $response["responseMessage"] !== "success") {
            return back()->withErrors($response["responseMessage"]);
        }
        
        // dd($request);

        $newProfile = $this->profile->create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "gender" => $request->gender,
            "city" => $request->city,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "phone_number" => $request->phone_number,
            "user_type" => $request->user_type,
            "is_active" => 1,
        ]);

        if($newProfile) {
            \DB::table("interest")->insert(["user_profile_id" => $newProfile->id,"value" => 0.00]);
            foreach($this->category->defaultAccounts() as $data) {
                foreach($data->subCategories as $account) {
                    $accountData = (object) array(
                        "uid" => $newProfile->id,
                        "category_id" => $account->id,
                        "account_number" => $response["responseBody"]["accountNumber"],
                        "account_ref" => $response["responseBody"]["accountReference"],
                        "bank" => $response["responseBody"]["bankName"],
                        "bankCode" => $response["responseBody"]["bankCode"],
                        "currency" => $response["responseBody"]["currencyCode"],
                    );
                    $this->accountservice->createAccount($accountData);
                }
            }
            return back()->withMessage("profile created successful");
        } else {
            return response()->json("An error creating your profile. Try again");
        }

        return back()->withErrors("A network error occured creating your account. Try again");
    }

    public function edit($id) {
        $data = $this->profile->find($id);
        if($data) {
            return view("website.admin.users.edit")->withUser($data);
        }

        return back()->withErrors(["Not found"]);
    }

    public function getProfile($id) {

        $data = $this->profile->find($id);
        if($data) {
            return view("website.admin.users.profile")->withUser($data);
        }

        return back()->withErrors("No record found");
    }

    public function update($id, array $request) {
        $found = $this->profile->find($id);

        if($found) {
            $found->update($request);
            return back()->withMessage("Profile updated");;
        }

        return back()->withErrors("No matching profile found.");
    }

    public function delete($id) {
        $found = $this->profile->find($id);

        if($found) {
            $found->accounts()->delete();
            $found->transactions()->delete();
            $found->loans()->delete();
            $found->delete();
            return back()->withMessage("Profile deleted");
        }
        return back()->withErrors("Error deleting profile.");
    }

    public function manageSocial($id) {

        $found = $this->social->where('user_profile_id', $id)->first();

        $action = request()->query('action') ? request()->query('action') : ' ';

        if($found && !empty($action)) {
            $status = $action === 'approve' ? true : false;
            $this->social->where('user_profile_id', $id)->update(['status' => $status, 'approval_status' => $status]);

            $details = [
                'first_name' => $found->user->first_name,
                'type' => 'approval',
                'email' => $found->user->email,
                'status' => $action.'d'
            ];

            \dispatch(new SasJob($details));

            return back()->withMessage("Social networking details ".\ucwords($action).'d');
        }

        return back()->withErrors("No matching record found.");

    }

    public function manageEmployment($id) {

        $found = $this->employment->find($id);

        $action = request()->query('action') ? request()->query('action') : ' ';

        if($found && !empty($action)) {
            $status = $action === 'approve' ? true : false;
            $found->update(['status' => $status, 'approval_status' => $status]);
            return back()->withMessage("Employment details ".\ucwords($action).'ed');
        }

        return back()->withErrors("No matching record found.");

    }


    public function manageKin($id) {

        $found = NextOfKin::find($id);

        $action = request()->query('action') ? request()->query('action') : ' ';

        if($found && !empty($action)) {
            $status = $action === 'approve' ? true : false;
            $found->update(['status' => $status]);
            return back()->withMessage("Next of Kin details ".\ucwords($action).'ed');
        }

        return back()->withErrors("No matching record found.");

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
