<?php
namespace App\Repositories\Admin;

use App\Account;
use App\Jobs\TransactionJob;
use App\LoanCategory;
use App\Loan;
use App\Http\Resources\LoanResource;
use App\Monnify\Monnify;
use App\Transaction;

class LoanRepository {

  protected $loan;
  protected $userloan;

  public function __construct(LoanCategory $loan, Loan $userloan) {
    $this->loan = $loan;
    $this->userloan = $userloan;
  }

  public function index() {
    $loans = $this->loan->orderBy("created_at", "desc")->get();

    if($loans) {
      return view("website.admin.loan.index")->withLoans($loans);
    }
    else {
      return back()->withErrors("Could not fetch loans");
    }
  }

  public function create() {
    return view("website.admin.loan.new-loan");
  }

  public function store($request) {

    if($request->has('interest_on_default') && $request->interest_on_default =='fixed') {
      $interest_amount = $request->interest_amount;
    } else {
      $interest_amount = $request->interest_percent;
    }

    if($request->interest_type == "monthly") {
      $request->interest_rate = $request->interest_rate * 30;
    } 



    $loan = $this->loan->create([
      "name" => $request->name,
      "type" => $request->type,
      "interest_rate" => $request->interest_rate,
      "interest_type" => $request->interest_type,
      "interest_on_default" => $request->interest_on_default,
      "max_amount" => $request->max_amount,
      "max_duration" => $request->max_duration,
      "interest_amount" => $interest_amount,
      "status" => 1,
      ]);

      if($loan) {
        return back()->withMessage("Loan category created");
      }

      return back()->withErrors("Could not create new category");
  }

  public function show($id) {
    $loan = $this->loan->find($id);
    if($loan) {
      return view("website.admin.loan.show");
    }

    return back()->withErrors("Loan not found");
  }

  public function edit($id) {
    $found = $this->loan->find($id);
    if($found) {
      return view("website.admin.loan.edit")->withLoan($found);
    }

    return back()->withErrors("No matching record found");
  }

  public function update($id, $request) {
    if($request['interest_on_default'] =='fixed') {
      $interest_amount = $request['interest_amount'];
    } else {
      $interest_amount = $request['interest_percent'];
    }

    if($request['interest_type'] == "monthly") {
      $request['interest_rate'] = $request['interest_rate'] * 30;
    } 

    $found = $this->loan->find($id);
    if($found) {
      $data = (array) $request;
      $save = $found->update($request);
      if($save) {
        return back()->withMessage("Data update successful");
      }
      return back()->withErrors("Could not save changes");
    }
    return back()->withErrors("No matching record found");
  }

  public function delete($id) {
    $loan = $this->loan->find($id);
    if($loan) {
      $delete = $loan->delete($id);
      if($delete) {
        return back()->withMessage("Record deleted successful");
      }
      return back()->withErrors("Could not delete record");
    }
    return back()->withErrors("No matching record found");
  }

  public function userLoans() {
    $data = $this->userloan->orderBy("approval_status", "desc")->get();

    return view("website.admin.loan.loan-requests")->withLoans($data);
  }

  public function approve($id, $request) {
    $loan = $this->userloan->find($id);
    if($request->decision === "Approved") {
      $status = 1;
    }
    else {
      $status = 0;
    }

    $save = $loan->update(["approval_status" => $request->decision, "status" => $status]);

    if($save) {
        if($request->decision === "Approved") {
            Transaction::create([
                "user_profile_id" => $loan->user_profile_id,
                "ref" => (new Monnify)->refCode(),
                "account_id" => $loan->user->accounts()->first()->id,
                "amount" => $loan->amount,
                "type" => "credit",
                "sub_type" => "Loan grant",
                "beneficiary" => $loan->user->first_name.' '.$loan->user->last_name,
                "vendor" => "Mavunifs",
                "description" => "Loan approved. Amount approved N".$loan->amount,
                "status" => "success",
            ]);
        }
      return back()->withMessage("Loan update was successful");
    }

    return back()->withErrors("Could not save changes");
  }

  public function manage($id) {
      $option = request()->query('option');
      $action = $option ? $option : null;
      $loan = $this->userloan->find($id);
      $account =  Account::where('user_profile_id', $loan->user_profile_id)->first();
    \Log::info((array) $account->user);
      if(!empty($action))  {
          $status = $action === 'approve' ? true : false;
          $save = $loan->update(["approval_status" => $action.'d', "status" => $status]);
          if($action === 'approve') {
              $account->update([
                  'amount' => $loan->amount,
                  'current_balance' => $account->current_balance + $loan->amount,
                  'prev_balance' => $account->current_balance
              ]);
              $details = (object)array(
                  "type" => "credit",
                  "email" => $account->user->email,
                  "name" => $account->last_name,
                  "amount" => $loan->amount,
                  "service" => ucwords($loan->category->name) . ' ' . ucwords($loan->category->type),
                  "status" => "success",
                  "reference" => (new Monnify)->refCode()
              );
              dispatch(new TransactionJob($loan->user->email, $details));
          }
          if($save) {
              return back()->withMessage("Loan ".$action."d successfully");
          }
      }

      return back()->withErrors("Could not save changes");
  }
}
