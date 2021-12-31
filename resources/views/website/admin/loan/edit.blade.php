@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        
        <div class="mb-3 col-lg-12">
            <h3 class="h4">Update Loan plan</h3>
        </div>

        <div class="col-lg-6 col-md-6 col-12">
            @include('includes.messages')
           <div class="wrapper shadow-sm">
           <form action="/admin/loan/{{$loan->id}}" method="POST" enctype="application/x-www-form-urlencoded">
                        @csrf
                        <div class="row mb-2">
                            <div class="col">
                                <label for="name">Loan name:</label>
                                <input type="text" value="{{$loan->name}}" name="name" id="name" class="form-control" placeholder="Name of loan plan" required>
                            </div>
                            <div class="col">
                                <label for="type">Type:</label>
                                <select name="type" id="loan_type" class="form-control">
                                    <option value="{{$loan->type}}">{{$loan->type}}</option>
                                    <option value="short term">Short term</option>
                                    <option value="long term">Long term</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="interest_rate">Interest rate:</label>
                                <input type="text" name="interest_rate" value="{{$loan->interest_rate}}" placeholder="Loan interest rate" class="form-control" required>
                            </div>
                            <div class="col" id="short_term">
                                <label for="interest_type">Interest type:</label>
                                <select name="interest_type"  class="form-control">
                                <option value="{{$loan->interest_type}}">{{$loan->interest_type}}</option>
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="max_amount">Maximum amount:</label>
                                <input type="text" value="{{$loan->max_amount}}"  name="max_amount" placeholder="Maximum amount per request" class="form-control" required>
                            </div>
                            <div class="col" id="short_term">
                                <label for="max_duration">Maximum duration:</label>
                                <input type="text" value="{{$loan->max_duration}}"  name="max_duration" placeholder="Maximum duration per request" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="interest_on_default">Interest on loan settlement default</label>
                                <select name="interest_on_default" id="interest_on_default"  class="form-control" required>
                                <option value="{{$loan->interest_on_default}}">{{$loan->interest_on_default}}</option>
                                <option value="fixed">Fixed</option>
                                <option value="compound">Compound</option>
                                </select>
                            </div>
                            <div class="col" id="interest_amount">
                                <label for="interest_amount">Amount at default <em>()</em>:</label>
                                <input type="text"  value="{{$loan->interest_type}}"name="interest_amount" class="form-control" id="interest_amount" placeholder="Amount in figure" >
                            </div>
                            <div class="col hidden" id="interest_percentage">
                                <label for="interest_percent">Amount in percentage(%)</label>
                                <input type="text" name="interest_percent" class="form-control" placeholder="Intereset in percentage" >
                            </div>
                        </div>
                        <div class="">
                            <button class="btn animated pulse slower btn-lg bg-gradient-primary text-white">
                                Submit <i class="fas fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </form>

               <!--<form action="/admin/loan/{{$loan->id}}" method="POST" enctype="application/x-www-form-urlencoded">
                   @csrf
                   @method("PUT")
                   <div class="row mb-2">
                       <div class="col">
                           <label for="name">Name</label>
                           <input type="text" value="{{$loan->name}}" name="name" id="name" class="form-control" required>
                       </div>
                       <div class="col">
                        <label for="type">Type:</label>
                        <select name="type" id="loan_type" class="form-control">
                            <option value="{{$loan->type}}">{{$loan->type}}</option>
                            <option value="short term">Short term</option>
                            <option value="long term">Long term</option>
                        </select>
                    </div>
                   </div>
                   <div class="row mb-2">
                        <div class="col">
                            <label for="interest_rate">Interest rate:</label>
                            <input type="text" name="interest_rate" value="{{$loan->interest_rate}}" placeholder="Loan interest rate" class="form-control" required>
                        </div>
                        <div class="col" id="short_term">
                            <label for="interest_type">Interest type:</label>
                            <select name="interest_type"  class="form-control">
                                <option value="{{$loan->interest_type}}">{{$loan->interest_type}}</option>
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="col hidden" id="long_term">
                            <label for="interest_type">Interest type:</label>                            
                            <select name="interest_type"  class="form-control">
                                <option value="{{$loan->interest_type}}">{{$loan->interest_type}}</option>
                                <option value="daily">Daily</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="interest_on_default">Interest on loan settlement default</label>
                            <select name="interest_on_default" id="interest_on_default"  class="form-control" required>
                                <option value="{{$loan->interest_on_default}}">{{$loan->interest_on_default}}</option>
                                <option value="fixed">Fixed</option>
                                <option value="compound">Compound</option>
                            </select>
                        </div>
                        <div class="col" id="interest_amount">
                            <label for="interest_amount">Amount at default <em>()</em>:</label>
                            <input type="text" name="interest_amount" value="{{$loan->interest_amount}}" class="form-control" id="interest_amount" placeholder="Amount in figure" >
                        </div>
                        <div class="col hidden" id="interest_percentage">
                            <label for="interest_percent">Amount in percentage(%)</label>
                            <input type="text" value="{{$loan->interest_amount}}" name="interest_percent" class="form-control" placeholder="Intereset in percentage" >
                        </div>
                    </div>
                   <div class="form-group">
                       <label for="status">Status:</label>
                       <select name="status" id="status" class="form-control">
                           @if ($loan->status)
                               <option value="{{$loan->status}}">
                                   <span class="text-success">Active<span>
                               </option>
                               <option value="0">
                                   <span class="text-danger">Deactivate<span>
                               </option>
                           @else
                               <option value="{{$loan->status}}">
                                   <span class="text-danger">Inactive<span>
                               </option>
                               <option value="1">
                                   <span class="text-sucess">Activate<span>
                               </option>
                           @endif
                       </select>
                   </div>
                   <div class="">
                       <button class="btn btn-lg bg-gradient-primary text-white">
                           Save <i class="fas fa-arrow-circle-right"></i>
                       </button>
                   </div>
               </form>
-->           </div>
       </div>
    </div>
@endsection