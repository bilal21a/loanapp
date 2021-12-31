@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-3">
            @include("includes.messages")
        </div>

        <div class="card mb-3 ">
            <div class="card-header">Debt Recovery</div>
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
            </div>
        </div>


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recovery Profile List</h6>
            </div>
            <div class="card-body">
                <div class="table-ressponsive table-responsive-lg">
                    <table class="table table-boredered table-hover " id="dataTable">
                        <thead>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Loan Date</th>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Last Amount To Pay</th>
                        <th>Monthly amount to pay</th>
                        <th>Last amount to pay</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>End Date balance</th>
                        <th>Status</th>
                        </thead>
                        <tbody>
                        <?php $i = 1; $j = 1; ?>
                        @foreach ($loans as $loan)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>
                                    {{$loan->user->first_name.' '.$loan->user->last_name}}
                                </td>
                                <td>{{$loan->request_date}}</td>
                                <td>{{$loan->amount}}%</td>
                                <td>{{$loan->interest}}</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->monthly_payment_amount}}@endif</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->monthly_payment_amount}}@endif</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->last_amount_to_pay}}@endif</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->start_date}}@endif</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->end_date}}@endif</td>
                                <td>@if($loan->recoveryPlan != null){{$loan->recoveryPlan->end_date_balance}}@endif</td>
                                <td>
                                    @if ($loan->is_settled)
                                        <span class="text-success">Settled</span>
                                    @else
                                        <span class="text-danger">Unsettled</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
