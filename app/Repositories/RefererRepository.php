<?php


namespace App\Repositories;

use App\Jobs\TransactionJob;
use App\Referer;
use App\Http\Resources\ProfileResource;
use App\Profile;
use App\Transaction;
use App\Account;
use App\AgentApproval;


class RefererRepository
{
    protected  $referer;
    protected  $priofile;
    protected  $account;
    protected $agent;

    public function __construct(Referer $referer, Profile $profile, Account $account, AgentApproval $agent)
    {
        $this->referer = $referer;
        $this->profile = $profile;
        $this->account = $account;
        $this->agent = $agent;
    }

    public function index() {
        $data = $this->referer->orderBy('created_at', 'DESC')->get();

        return response()->json(['status' => true, 'data' => ProfileResource::collection($data)]);
    }

    public function store($request) {
        $account = $this->account->where('account_number', $request->referer_code)->first();

        if(!empty($account)) {
            $profile = auth()->guard('profile')->user();
            $referer = $this->profile->find($account->user_profile_id);

            $checkReferer = $this->referer->where('user_profile_id', auth()->guard('profile')->user()->id)->first();

            if(!empty($checkReferer)) {
                return response()->json(['status' => false, 'message' => 'Oops, you already linked with a referer']);
            }

            $this->referer->create([
                'user_profile_id' => $profile->id,
                'referer_id' => $referer->id,
                'approval_status' => 'pending',
                'status' => false
            ]);
            return response()->json(['status' => true, 'message' => 'Your referer has been linked, Please wait while he verifies and approve th request ']);
        }

        return response()->json(['status' => false, 'message' => 'Could not link up referer, Invalid referer code']);
    }

    public function manage($id) {
        $option = request()->query('option')  && !empty(request()->query('option')) ? request()->query('option')
            : null;

        if(!is_null($option)) {

            $user = auth()->guard('profile')->user();
            //\Log::info($user);
            $status = $this->referer->where('user_profile_id', $id)->where('referer_id', $user->id)->first();
            $account = $this->account->where('user_profile_id', $id)->first();
            $profile = $this->profile->find($id);
            $agentApproval = $this->agent->where('user_profile_id', $id)->first();

            if(!empty($account) && $status === 'pending' && $option === 'approve') {
//                $account->update([
//                    'amount' => config('settings.referer_bonus'),
//                    'current_balance' => $account->current_balance + config('settings.referer_prize'),
//                    'prev_balance' => $account->current_balance,
//                ]);

                Transaction::create([
                    "user_profile_id" => $profile->id,
                    "ref" => $this->refCode(),
                    "amount" => config('settings.referer_bonus'),
                    "account_id" => $account->id,
                    "vendor" => "Mavunifs",
                    "beneficiary" => $profile->first_name.' '.$profile->last_name,
                    "status" => "success",
                    "type" => "credit",
                    "sub_type" => "Referer bonus",
                    "description" => "Referer bonus"
                ]);

                $details = (object) array(
                    "type" => "credit",
                    "email" => $profile->email,
                    "name" => $profile->last_name,
                    "amount" => config('settings.referer_bonus'),
                    "service" => "Referer bonus",
                    "status" => "success",
                    "reference" => $this->refCode()
                );
                \dispatch(new TransactionJob($profile->email, $details));

                $status->update(['approval_status' => 'approved', 'status' => true]);
                return response()->json(['status' => true, 'message' => 'Referee '.$option.'ed']);
            }

            if(!empty($agentApproval)) {
                $agentApproval->update([
                    'points' => 0,
                    'status' => $option === 'approve' ? true : false,
                ]);
            }
            else {
                $this->agent->create([
                    'agent_id' => $status->referer_id,
                    'user_profile_id' => $id,
                    'points' => 30,
                    'status' => true
                ]);
            }

            $status->update(['approval_status' => 'declined', 'status' => false]);
            return response()->json(['status' => true, 'message' => 'Referee request declined']);

        }
        return response()->json(['status' => false, 'message' => 'Could not perform operation']);
    }

    public function refCode()
    {
        return bin2hex(random_bytes(5));
    }
}
