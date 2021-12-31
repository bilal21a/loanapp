<?php
namespace App\Repositories;

use App\Loan;
use App\Kyc;
use App\LoanCategory;
use App\Http\Resources\LoanResource;
use Carbon\Carbon;

class LoanRepository
{
    protected $loan;
    protected $kyc;

    public function __construct(Loan $loan, Kyc $kyc)
    {
        $this->loan = $loan;
        $this->kyc = $kyc;
    }

    public function index()
    {
        $data = $this->loan->where('status', 1)->get();
        return response()->json(['status' => true, "data" => LoanResource::collection($data)]);
    }

    public function store($request)
    {
        $kyc = $this->kyc->where("user_profile_id", auth()->guard("profile")->user()->id)->first();
        $user = auth()->guard('profile')->user();
        $loanCategory = (new LoanCategory)->find($request->loan_id);
        $loanCount = $this->loan->where("status", 1)->where("user_profile_id", auth()->guard("profile")->user()->id)->count();

        if ($loanCount >= 1) {
            return response()->json(["status" => false, "message" => "You have an active loan"]);
        }

        if(!$user->kycApprovalStatus()) {
            return response()->json(["status" => false, "message" => "You are not eligible for loan because you are yet to complete your SAS or it is still pending approval."]);
        }

        if ($request->amount > $loanCategory->max_amount) {
            return response()->json(["status" => false, "message" => "Requested amount exceeds maximum loan amount"]);
        }

        if ($request->duration > $loanCategory->max_duration) {
            return response()->json(["status" => false, "message" => "Requested duration exceeds maximum loan duration"]);
        }

        $loan = $this->loan->create([
          "loan_id" => $request->loan_id,
          "user_profile_id" => auth()->guard("profile")->user()->id,
          "request_date" => Carbon::now()->toDateString(),
          "amount" => $request->amount,
          "duration" => $request->duration,
          "interest" => $this->loan->loanInterest($request->amount, $loanCategory->interest_rate, $request->duration, $loanCategory->interest_ype),
          "due_date" => Carbon::now()->addDays($request->duration)->toDateString(),
          "approval_status" => "pending",
          "is_settled" => 0
        ]);

        if ($loan) {
            return response()->json(["status" => true, "data" => new LoanResource($loan)]);
        }

        return response()->json(["status" => false, "message" => "Could not submit loan request"]);
    }

    public function userLoans()
    {
        $loans = $this->loan->where("user_profile_id", auth()->guard("profile")->user()->id)->get();
        if ($loans) {
            return LoanResource::collection($loans);
        } else {
            return response()->json(["status" => false, "message" => "Could not fetch loans"]);
        }
    }

    public function show($id)
    {
        $loan = $this->loan->findOrFail($id);
        if ($loan) {
            return new LoanResource($loan);
        }

        return response()->json(["status" => false, "message" => "Loan not found"]);
    }

    public function update($id, array $request)
    {
        $found = $this->loan->find($id);

        if ($found) {
            $save = $found->update($request);
            if ($save) {
                return new LoanResource($save);
            }
            return response()->json(["status" => false, "message" => "Could not save changes"]);
        }
        return response()->json(["status" => false, "message" => "No matching record found"]);
    }

    public function delete($id)
    {
        $loan = $this->loan->find($id);
        if ($loan) {
            $delete = $loan->delete($id);
            if ($delete) {
                return new LoanResource($delete);
            }
            return response()->json(["status" => false, "message" => "Could not delete record"]);
        }
        return response()->json(["status" => false, "message" => "No matching record found"]);
    }

    public function repayment($request)
    {
        $loan = $this->loan->where("user_profile_id", auth()->guard("profile")->user()->id)->
                              where("is_settled", 0)->where("approval_status", "Approved")->latest()->first();

        if ($request->amount < $loan->interest + $loan->amount) {
            return response()->json(["status" => false, "message" => "Loan must be paid back in full with interest."]);
        }

        $paymentData = (object) array(
        "auth_code" => Auth::user()->id->guard("profile")->user()->cards->first()->auth_code,
/*      "auth_code" => auth()->guard("profile")->user()->cards->first()->auth_code,*/
      "email" => auth()->guard("profile")->user()->email,
      "amount" => $loan->amount + $loan->interest,
    );

        $response = \Payment::chargeAuthorization($paymentData);

        if (!$response["status"] || $response["data"]["status"] === "failed") {
            return response()->json($response);
        }

        $save = $loan->update(["is_settled" => 1, "status" => 0]);

        if ($save) {
            return response()->json(["status" => true, "message" => "Loan settled"]);
        }

        return response()->json(["status" => false, "message" => "Could not settle loan"]);
    }

    public function cancelRequest($id)
    {
        $loan = $this->loan->find($id);

        if ($loan->user_profile_id === auth()->guard("profile")->user()->id && $loan->approval_status === "pending") {
            $loan->delete();
            return response()->json(["status" => true, "message" => "Loan request canceled"]);
        }

        return response()->json(["status" => false, "message" => "Cannot not cancel an active loan"]);
    }
}
