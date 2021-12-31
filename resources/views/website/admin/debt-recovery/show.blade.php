@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        @include("includes.messages")
    </div>

    <div class="card mb-3 ">
        <div class="card-header">Debt Details</div>
        <div class="card-body">
            <div class="col-md-12 col-lg-12">
                <h4>Debt Profile</h4>
                <hr>
            </div>
            <div class="form-group">
                <form id="userForm" action="{{route('debt.find')}}" method="post">
                    @csrf
                    <div class="btn-group">
                        <input type="text" id="user_id" name="user_id" placeholder="User ID" required>
                        <button class="btn btn-primary btn-sm">Get Details</button>
                    </div>
                </form>
            </div>
            @if($loan)
            <div class="row mb-3 mt-3">
                <div class="col-md-6 col-lg-6">
                    <h5>Details</h5>
                    <hr>
                    <p><strong>Name:</strong> <span>{{ucfirst($loan->user->first_name.' '.$loan->user->last_name)}}</span></p>
                    <p><strong>Email:</strong> <span>{{strtolower($loan->user->email)}}</span></p>
                    <p><strong>Phone Number:</strong> <span>{{trim($loan->user->phone_number)}}</span></p>
                    <p><strong>Gender:</strong> <span>{{$loan->user->gender}}</span></p>
                    <p><strong>Date Joined:</strong> <span>{{$loan->user->created_at->toFormattedDateString()}}</span></p>

                    <div class="mt-3 mb-3">
                        <h5>Wallet</h5>
                        <p><strong>Current balance:</strong> <span>ZAR{{number_format($loan->user->accounts()->first()->current_balance, 2)}}</span></p>
                        <p><strong>Previous balance:</strong> <span>ZAR{{number_format($loan->user->accounts()->first()->prev_balance,2)}}</span></p>
                        <p><strong>Last Transaction:</strong> <span>ZAR{{number_format($loan->user->accounts()->first()->current_balance, 2)}}</span></p>
                        <p><strong>Account Number:</strong> <span>{{$loan->user->accounts()->first()->account_number}}</span></p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <h5>Loan</h5>
                    <hr>
                    <p><strong>Loan Date:</strong> <span>{{$loan->request_date}}</span></p>
                    <p><strong>Due Date:</strong> <span>{{$loan->due_date}}</span></p>
                    <p><strong>Amount:</strong> <span>ZAR{{number_format($loan->amount,2)}}</span></p>
                    <p><strong>Interest:</strong> <span>ZAR{{number_format($loan->interest,2)}}</span></p>
                    <p><strong>Duration:</strong> <span>{{$loan->duration}}</span></p>
                    <p><strong>Status:</strong> <span class="text-success">{{ucfirst($loan->approval_status)}}</span></p>
                    <p><strong>Settlement Status:</strong> <span class="text-danger">Unsettled</span></p>
                    <button type="button" id="showDebtForm" class="btn btn-primary">Create repayment Plan</button>
                    <div class="mt-3 mb-3" id="debtForm" style="display: none">
                        <form action="{{route('recovery.create')}}" method="post">
                            @csrf
                            <input type="hidden" name="loan_id" value="{{$loan->id}}">
                            <div class="row">
                                <div class="col">
                                    <label for="amount_per_month" class="form-label">Amount to pay monthly</label>
                                    <input
                                        type="number" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->monthly_payment_amount}}" @endif
                                        name="monthly_pay" id="monthly_pay"
                                        class="form-control" placeholder="0.00" required
                                    >
                                </div>
                                <div class="col">
                                    <label for="last_amount" class="form-label">Last Amount to pay</label>
                                    <input type="number" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->last_amount_to_pay}}" @endif
                                           name="last_pay" id="last_pay"
                                           class="form-control" placeholder="0.00" required
                                    >
                                </div>
                                <div class="col">
                                    <label for="end_date_balance" class="form-label">End date Balance</label>
                                    <input type="number" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->end_date_balance}}" @endif
                                           name="end_date_balance" id="end_date_balance"
                                           class="form-control" placeholder="0.00" required
                                    >
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <div class="col">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->start_date}}" @endif
                                           name="start_date" id="start_date"
                                           class="form-control" required
                                    >
                                </div>
                                <div class="col">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->end_date}}" @endif
                                           name="end_date" id="end_date"
                                           class="form-control" required
                                    >
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_pay_date" class="form-label">Last Date to Pay</label>
                                <input type="date" @if($loan->recoveryPlan != null) value="{{$loan->recoveryPlan->last_date_to_pay}}" @endif
                                       name="last_date_to_pay" id="last_date-to_pay"
                                       class="form-control" required
                                >
                            </div>
                            <button class="btn btn-secondary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            @endif
        </div>
    </div>

</div>
@endsection
