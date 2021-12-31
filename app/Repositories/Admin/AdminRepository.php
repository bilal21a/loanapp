<?php 
namespace App\Repositories\Admin;

use App\Admin;
use App\Savings;
use App\Profile;
use App\Account;
use App\UserInvestment;
use App\EditEnv;

class AdminRepository {

    protected $admin;
    protected $category;
    protected $savings;
    protected $account;

    public function __construct(Admin $admin, Savings $savings, Account $account) {
        $this->admin = $admin; 
        $this->savings = $savings;
        $this->account = $account;
    }

    public function index() {
        return view("website.admin.index");
    }

    public function users() {
        $data = $this->admin->orderBy("name", "asc")->paginate(10);
        return view("website.admin.users")->withUsers($data);
    }

    public function store($request) {
        $validator = \Validator::make($request->all(), [
            "name" => "required",
            "gender" => "required",
            "phone_number" => "required",
            "email" => "required|email",
            "password" => "required|min:6|confirmed",
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $accountExists = $this->admin->where("email", $request->email)->count();

        if($accountExists >= 1) {
            return back()->withErrors("User already exists with this email");
        }

        $save = $this->admin->create([
            "name" => $request->name,
            "gender" => $request->gender,
            "phone_number" => $request->phone_number,
            "email" => $request->email,
            "password" => \Hash::make($request->password),
            "is_active" => 1,
        ]);

        if($save) {
            return back()->withMessage("Account created successfully");
        }

        return back()->with("message", "An error occoured creating your account");
    }

    public function find($id) {
        $found = $this->admin->findOrFail($id);

        if($found) {
            return view("website.admin.profile")->withUser($found);
        }

        return back()->with("error", "Not found");
    }

    public function edit($id) {
        $user = $this->admin->find($id);
        if($user) {
            return view("website.admin.edit")->withUser($user);
        }
    }

    public function update($id, array $request) {
        $update = $this->admin->findOrFail($id);

        $update->update($request);
        if($update) {
            return back()->with("message", "Account updated");
        }

        return back()->with("message", "Not found");
    }

    public function delete($id) {
        $found = $this->admin->findOrfail($id);

        if($found) {
            $found->delete();
        }

        return back()->with("message", "Not found");
    }

    public function settings() {
        $data = (object) (new EditEnv)->getContent();

        $selected = array( 
            // "APP_NAME" => $data->APP_NAME,
            "PAYSTACK_PUBLIC_KEY" => $data->PAYSTACK_PUBLIC_KEY,
            "PAYSTACK_SECRET_KEY" => $data->PAYSTACK_SECRET_KEY,
            "PAYSTACK_MARCHANT_EMAIL" => $data->PAYSTACK_MARCHANT_EMAIL,
            "PAYSTACK_BASE_URL" => $data->PAYSTACK_BASE_URL,
            "MONNIFY_SECRET_KEY" => $data->MONNIFY_SECRET_KEY,
            "MONNIFY_API_KEY" => $data->MONNIFY_API_KEY,
            "MONNIFY_CONTRACT_CODE" => $data->MONNIFY_CONTRACT_CODE,
            "MONNIFY_BASE_URL" => $data->MONNIFY_BASE_URL,
            "SHAGO_API_KEY" => $data->SHAGO_API_KEY,
            "MOBILENG_API_USERID" => $data->MOBILENG_API_USERID,
            "MOBILENG_API_PASS" => $data->MOBILENG_API_PASS
        );
        return view("website.admin.settings")->withEnvs($selected); 
    }

    public function saveSettings($request) {
        if($request->has("keyname")) { 
            
            if((new EditEnv)->keyExists($request->keyname)){
                $data = array($request->keyname => (new EditEnv)->sanitize($request->keyvalue));
                $save = (new EditEnv)->updateEnv($data);
                if($save)
                return back()->withMessage("Saved");
            } 
            else { 
                return back()->withErrors("Key not found");
            }

            return back()->withErrors("No Key name selected");
        }

        return back()->withErrors("Your settings could not be processed");
    }

    public function dashboard() {
        $autosavings = $this->savings->auto_savings();
        $quicksavings = $this->savings->quick_savings();
        $withdrawals = $this->savings->total_withdrawal();
        $accountbalance = $this->account->account_balance();
        $savings = Savings::with("user")->orderBy("created_at", 'desc')->get();
        $users = (new Profile)->total_users();
        $investment = (new UserInvestment)->total_investment();

        return view("website.admin.dashboard")->withTransactions($savings)
                        ->withUsers($users)->withInvestments($investment)
                        ->withQuicksavings($quicksavings)
                        ->withWithdrawal($withdrawals)
                        ->withAutosavings($autosavings)
                        ->withBalance($accountbalance);
    }

}