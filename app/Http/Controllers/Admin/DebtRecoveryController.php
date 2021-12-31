<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Loan;
use App\Http\Resources\LoanResource;
use App\DebtRecovery;

class DebtRecoveryController extends Controller
{
    protected  $loan;
    protected $recovery;

    public function __construct(Loan $loan, DebtRecovery $recovery)
    {
        $this->loan = $loan;
        $this->recovery = $recovery;
    }

    public function showRecoveryPage() {
        $loans = $this->loan->where("approval_status", "approved")->where("is_settled", 0)->orderBy("created_at","DESC")->paginate(20);
        return view("website.admin.debt-recovery.index")->withLoans($loans);
    }

    public function getDebt(Request $request)
    {
        $debt = $this->loan->where("user_profile_id", $request->user_id)->where("approval_status", "approved")->where("is_settled", 0)->first();

        if($debt)
        {
            if($request->ajax()) {
                return response()->json(["status" => true, "message" => "success", "data" => new LoanResource($debt)], 200);
            }

            return redirect()->route("debt.show", $request->user_id);
            //return back()->withLoan($debt);
            //$debts = $this->loan->where("approval_status", "approved")->where("is_settled", 0)->get();
        }

        if($request->ajax()) {
            return response()->json(["status" => false, "message" => "No record found", "data" => []], 404);
        }

        return back()->withErrors("No record found");

    }

    public function show($id)
    {
        $debt = $this->loan->where("user_profile_id", $id)->where("approval_status", "approved")->where("is_settled", 0)->first();

        if($debt)
        {
            if(request()->ajax()) {
                return response()->json(["status" => true, "message" => "success", "data" => new LoanResource($debt)], 200);
            }

            return  view("website.admin.debt-recovery.show")->withLoan($debt);
        }

        if(request()->ajax()) {
            return response()->json(["status" => false, "message" => "No record found", "data" => []], 404);
        }

        return back()->withErrors("No record found");

    }

    public function createPlan(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "monthly_pay" => "required|numeric|between:1,999999.99",
            "last_pay" => "required|numeric|between:1,999999.99",
            "end_date_balance" => "required|numeric|between:1,999999.99",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "last_date_to_pay" => "required|date",
            "loan_id" => "required|unique:debt_recoveries"
        ], ["loan_id.unique" => "This account has an existing recovery plan"]);

        if($validator->fails()) {
            if($request->ajax()) {
                return response()->json(["status" => false, "message" => "Enter a valid amount"]);
            }
            return redirect()->back()->withErrors($validator->errors());
        }

        $debt = $this->recovery->create([
            "loan_id" => $request->loan_id,
            "monthly_payment_amount" => $request->monthly_pay,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "last_amount_to_pay" => $request->last_pay,
            "last_date_to_pay" => $request->last_date_to_pay,
            "end_date_balance" => $request->end_date_balance

        ]);

        if($debt) {
            return back()->withMessage("Recovery plan created");
        }

        return back()->withErrors("Could not create_plan");
    }
}
