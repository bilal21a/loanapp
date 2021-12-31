@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-lg-7 mb-4">
            <h3 class="h4">Add Bank</h3>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
             @include('includes.messages')
            <div class="wrapper shadow-sm">
                <form action="{{route('bank.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Bank:</label>
                        <select name="bankCode" id="bank_code" class="form-control">
                            <option>Select Bank</option>
                            @foreach ($banks as $bank)
                                <option value="{{$bank["code"]}}">{{$bank["name"]}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="bank" id="bank">
                    </div>
                    <div class="form-group">
                        <label for="type">Account number:</label>
                        <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Account number" required>
                        <input type="hidden" name="uid" value="{{$uid}}">
                    </div>
                    <div class="">
                        <button class="btn bg-gradient-primary text-white">
                            Submit <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection