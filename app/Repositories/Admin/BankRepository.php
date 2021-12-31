<?php 
namespace App\Repositories\Admin;

use App\Bank;
use App\Profile;
use App\Http\Resources\BankResource;

class BankRepository {
    
    protected $bank;

    public function __construct(Bank $bank, Profile $profile) {
        $this->bank = $bank;
        $this->profile = $profile;
    }

    public function index() {
        $data = $this->profile->with("banks")->get();
        //$data = $this->bank->with("user")->orderBy("created_at", "desc")->get();
        return view("website.admin.bank.index")->withBanks(BankResource::collection($data));
    }

    public function create($id) {
        $uid = $this->profile->find($id)->id;
        $banks = \Payment::getBanks();
      if(!$banks["status"]) {
          return back()->withErrors("A network error has occured. try again");
      }
       return view("website.admin.bank.new-bank")->withUid($uid)->withBanks($banks["data"]);
    }

    public function addBank($request) {
        $response = \Payment::verifyBankAccount($request->account_number, $request->bankCode);
        if(!$response["status"]){
            return back()->withErrors($response);
        } 
        
        $user = $this->profile->findOrFail($request->uid);
        if($response["data"]["account_name"] !== strtoupper($user->first_name." ".$user->last_name)) {
            return back()->withErrors("Your name did not match the name on the account");
        }

        $userData = (object) array(
            "name" => $user->first_name." ".$user->last_name,
            "accountNumber" => $response["data"]["account_number"],
            "description" => "Mavunifs Saving's user fund transfer account",
            "bankCode" => $request->bankCode,
        );

        $rcp = \Payment::createTransferRecepient($userData);

        if(!$rcp["status"]){
            return back()->withErrors($rcp);
        }

        if(!$rcp["data"]["active"]){
            return back()->withErrors("Recipient account not active");
        } 

        $newBank = $this->bank->create([
            "user_profile_id" => $request->uid,
            "bank_name" => $request->bank,
            "account_number" => $request->account_number,
            "bank_code" => $request->bankCode,
            "recipient_code" => $rcp["data"]["recipient_code"],
        ]);

        if($newBank) {
            return back()->withMessage("Bank added successfully");
        }

        return back()->withErrors("An error occured adding bank. Try again");
    }

    public function getBank($id) {
        $bank = $this->bank->find($id);
        if($bank) {
            return view("website.admin.bank.show")->withBank(BankResource($bank));
        }

        return back()->withErrors("Not found");
    }

    public function update($id, array $request) {
        $bank = $this->bank->find($id);

        if($bank) {
            $this->bank->update($request);
            return back()->withMessage("Details updated");
        }

        return back()->withErrors("No matching record found.");
    }

    public function delete($id) {
        $bank = $this->bank->find($id);
        if($bank) {
            $bank->delete();
            return back()->withMessage("Deleted");
        }
        return back()->withErrors("Error deleting bank.");
    }

    public function verifyBVN($request) {
        $respone = \Payment::verifyBVN($request->bvn);

        if(!$respone["status"]) {
            return back()->withErrors($respone);
        }

        return back()->withMessage("BVN verified. VALID");
    }
}