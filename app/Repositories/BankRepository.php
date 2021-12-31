<?php
namespace App\Repositories;

use App\Bank;
use App\Profile;
use App\Kyc;
use App\Http\Resources\BankResource;
use App\Http\Resources\ProfileResource;

class BankRepository {

    protected $bank;

    public function __construct(Bank $bank, Profile $profile) {
        $this->bank = $bank;
        $this->profile = $profile;
    }

    public function index() {
        $data = $this->bank->orderBy("created_at", "desc")->get();
        return BankResource::collection($data);
    }

    public function addBank($request) {
        $response = \Payment::verifyBankAccount($request->account_number, $request->bankCode);
        if(!$response["status"]){
            return response()->json(["status" => false, "message" => $response]);
        }

        $user = auth()->guard("profile")->user();
        if($response["data"]["account_name"] !== strtoupper($user->first_name." ".$user->last_name)) {
            return response()->json([
                "status" => false,
                "message" => "Your name did not match the name on the bank account"
            ]);
        }

        $userData = (object) array(
            "name" => $user->first_name." ".$user->last_name,
            "accountNumber" => $response["data"]["account_number"],
            "description" => "Mavunifs Saving's user fund transfer account",
            "bankCode" => $request->bankCode,
        );

        $rcp = \Payment::createTransferRecepient($userData);

        if(!$rcp["status"]){
            return response()->json($rcp);
        }

        if(!$rcp["data"]["active"]){
            return response()->json(["message" => "Recipient account not active", "status" => false]);
        }

        $newBank = $this->bank->create([
            "user_profile_id" => auth()->guard("profile")->user()->id,
            "bank_name" => $request->bank,
            "account_number" => $request->account_number,
            "bank_code" => $request->bankCode,
            "recipient_code" => $rcp["data"]["recipient_code"],
        ]);

        if($newBank) {
            return new BankResource($newBank);
        }

        return response()->json(["status" => false, "message" => "An error occured adding bank. Try again"]);
    }

    public function getBank($id) {
        $bank = $this->bank->find($id);
        if($bank) {
            return new BankResource($bank);
        }

        return response()->json(["message" => "Not found", "status" => false]);
    }

    public function update($id, array $request) {
        $bank = $this->bank->find($id);

        if($bank) {
            $this->bank->update($request);
            return new BankResource($bank);
        }

        return response()->json(["message" => "No matching record found.", "status" => false]);
    }

    public function delete($id) {
        $bank = $this->bank->find($id);
        if($bank) {
            $bank->delete();
            return new BankResource($bank);
        }
        return response()->json(["message" => "Error deleting bank.", "status" => false]);
    }
    
    
    public function verifyBVN($request) {

        $response = \Monnify::verifyBVN($request->bvn);
        //\Log::info((array) $response);
        if(!$response["status"]) {
            return response()->json(["status" => false, "message" => "An API error occured"]);
        }
        
        $user = auth()->guard("profile")->user();
        $kyc = Kyc::where("user_profile_id", $user->id)->first();
        if(($response["data"]["surname"] === strtoupper($user->last_name) && $response["data"]["firstName"] === strtoupper($user->first_name) ) || $response["data"]["dateOfBirth"] === $kyc->dob || $response["data"]["mobileNo"] === $user->phone_number) {
            $kyc->update(["bvn" => $request->bvn]);
            $message = "BVN verification successful, not linked to account";
            $update = \Monnify::updateBVN($user->accounts->first()->account_ref, $request->bvn);
            if($response["responseMessage"] === "success" && isset($response["responseBody"])) {
                $message = "BVN verification successful";
            }
                        
            // if(isset($update["status"])  && $update["status"] === false) {
            //     $message = "BVN verification successful, not linked to account";
            // }
            return response()->json(["message" => $message, "status" => true, "data" => new ProfileResource($user)]);
        }
        else {
            return response()->json(["status" => false, "message" => "BVN did not match"]);
        }
        
        return response()->json(["status" => false, "message" => "A network error occured"]);
    }


}
