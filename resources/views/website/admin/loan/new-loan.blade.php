@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="col-md-6">
            @include('includes.messages')
            <div class="card">
                <h3 class="card-header">Create Loan Plan</h3>
                <div class="card-body shadow-sm">
                    <form action="{{route('loan.add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <div class="col">
                                <label for="name">Loan name:</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name of loan plan" required>
                            </div>
                            <div class="col">
                                <label for="type">Type:</label>
                                <select name="type" id="loan_type" class="form-control">
                                    <option value="short term">Short term</option>
                                    <option value="long term">Long term</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="interest_rate">Interest rate:</label>
                                <input type="text" name="interest_rate" placeholder="Loan interest rate" class="form-control" required>
                            </div>
                            <div class="col" id="short_term">
                                <label for="interest_type">Interest type:</label>
                                <select name="interest_type"  class="form-control">
                                    <option value="daily">Daily</option>
                                    <option value="monthly">monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="max_amount">Maximum amount:</label>
                                <input type="text" name="max_amount" placeholder="Maximum amount per request" class="form-control" required>
                            </div>
                            <div class="col" id="short_term">
                                <label for="max_duration">Maximum duration:</label>
                                <input type="text" name="max_duration" placeholder="Maximum duration per request" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="interest_on_default">Interest on loan settlement default</label>
                                <select name="interest_on_default" id="interest_on_default"  class="form-control" required>
                                    <option>Select One</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="compound">Compound</option>
                                </select>
                            </div>
                            <div class="col" id="interest_amount">
                                <label for="interest_amount">Amount at default <em>()</em>:</label>
                                <input type="text" name="interest_amount" class="form-control" id="interest_amount" placeholder="Amount in figure" >
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
                </div>
            </div>
        </div>

    </div>
@endsection
