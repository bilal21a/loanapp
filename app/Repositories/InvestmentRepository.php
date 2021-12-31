<?php
namespace App\Repositories;

use App\Http\Resources\ProfileResource;
use App\Investment;
use App\Monnify\Monnify;
use App\Profile;
use App\Account;
use App\Savings;
use App\UserInvestment;
use App\Transaction;
use App\PartnerReferer;
use App\Http\Resources\InvestmentResource;

class InvestmentRepository {

    protected $investment;
    protected $profile;
    protected $account;
    protected $savings;
    protected $userinvestment;


    public function __construct(Account $account, Savings $savings, Investment $investment, Profile $profile, UserInvestment $userinvestment) {
        $this->investment = $investment;
        $this->profile = $profile;
        $this->account = $account;
        $this->savings = $savings;
        $this->userinvestment = $userinvestment;
    }

    public function index() {
        $uid = auth()->guard("profile")->user()->id;
        $data = $this->userinvestment->where("user_profile_id", $uid)->get();

        return InvestmentResource::collection($data);
    }

    public function store($request) {

        $user = auth()->guard("profile")->user();
        $account = $this->account->where("user_profile_id", $user->id)->first();
        $investment = $this->investment->find($request->investment_id);
        $referer_id = null;
//        if (count($investment->referees) >= 10 ) {
//            return response()->json(["status" => false, "message" => "Sold out"]);
//        }

        if(!$investment) {
            return response()->json(["status" => false, "message" => "Investment not found"]);
        }

        $amount_to_invest = $investment->amount_per_investor * $request->slots;

        if(!$investment->status || $investment->amount == $investment->total_investment) {
           return response()->json(["status" => false, "message" => "Sold out"]);
        }

        if(!$this->investment->slotChecker(
            $investment->amount_per_investor,
            $request->slots,
            $investment->amount,
            $investment->total_investment
            )) {
            return response()->json([
                "status" => false, "message" => "The number of slots requested is unvavilable"], 422);
        }

        if ($amount_to_invest >= $account->current_balance) {
            return response()->json(["status" => false, "message" => "Insufficient fund"]);
         }

        if($request->has('ref_code') && !empty($request->ref_code)) {
            $referer = $this->userinvestment->where("referer_code", $request->ref_code)->first();
            if(empty($referer) || !$referer) {
                return response()->json(["status" => false, "message" => "Invalid referer code"]);
            }
            $referer_id = $referer->id;
        }

        if($account) {

            $data = $this->account->withdrawal(
                $amount_to_invest,
                $account->current_balance,
                $account->prev_balance
            );

            $totalInvestment = $investment->total_investment + $amount_to_invest;
            $debitaccount = $account->update([
                "amount" => $data["withdrawal_amount"],
                "current_balance" => $data["current_balance"],
                "prev_balance" => $data["prev_balance"],
            ]);

            if($debitaccount) {
                $referer_code = "MVF".time();
               $invt = $this->userinvestment->create([
                     "user_profile_id" => $user->id,
                     "investment_id" => $investment->id,
                     "amount" => $request->amount,
                     "slots" => $request->slots,
                     "referer_code" => $referer_code,
                     "status" => 1,
                ]);
               if(isset($referer_id)) {
                   PartnerReferer::create([
                       "paterner_id" => $referer_id,
                       "user_profile_id" => $user->id,
                   ]);
               }

                $refcode = (new Monnify())->refCode();
                $transaction =  (new Transaction)->transaction(
                    $user,
                    $refcode,
                    $request->amount,
                    $account->id,
                    "Kredda",
                    "success",
                    "debit",
                    "Investment",
                    'Investment'
                );
                $investment->update([
                    "total_investment" =>  $totalInvestment,
                ]);
                return response()->json([
                    "status" => true,
                    "message" => "Investment successful",
                    "user" => new ProfileResource($user),
                    "data" => new InvestmentResource($invt)
                ], 201);
            }
        }

        return response()->json(["status" => false, "message" => "Could not invest"]);

    }

    public function show($id) {
        $investment = $this->userinvestment->find($id);
        if($investment) {
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new InvestmentResource($investment)
            ]);
        }

        return response()->json(["status" => false, "message" => "Not found"]);
    }

    public function update($id, array $request) {
        $investment = $this->investment->findOrFail($id);
        if($investment->update($request)) {
            //return new InvestmentResource($investment);
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new InvestmentResource($investment)
            ]);
        }

        return response()->json(["status" => false, "message" => "Could not update data"]);
    }

    public function delete($id) {
        $found = $this->investment->findOrFail($id);
        if($found) {
            $found->delete();
            //return new InvestmentResource($found);
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new InvestmentResource($found)
            ]);
        }

        return response()->json(["status" => false, "message" => "Could not delete record"]);
    }

    public function invest($id) {
        $investment = $this->investment->find($id);
        if(!$investment->status || $investment->amount == $investment->total_investment) {
            //return "Sold out";
            return response()->json([
              "status" => false,
              "message" => "Sold out"
            ]);
        }

        $totalInvestment = $investment->total_investment + $investment->amount_per_investor;
       $invest =  $this->userinvestment->create([
            "user_profile_id" => auth()->guard('profile')->user()->id,
            "investment_id" => $investment->id,
            "amount" => $investment->amount_per_investor,
        ]);
        if($invest) {
            $investment->update([
                "total_investment" =>  $totalInvestment,
            ]);
            //return "done";
            return response()->json([
              "status" => true,
              "message" => "success",
              "data" => new InvestmentResource($investment)
            ]);
        }


    }

}
