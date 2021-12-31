@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-lg-7 mb-4">
            <h3 class="h4">Add Debit/Credit card</h3>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
             @include('includes.messages')
            <div class="wrapper borderless-field shadow-sm">
                <form action="{{route('card.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Card Number</label>
                        <input type="text" placeholder="Card Number" name="cardNumber" id="card_number" class="form-control" required>
                    </div>
                    <div class="row mb-2">                                
                        <div class="col">
                            <label for="expiryMonth">Expiry</label>
                            <input type="number" name="exiryMonth" id="exiryMonth" class="form-control form-control-sm input-sm" placeholder="Month" required>
                        </div>
                        <div class="col">
                            <label for="expiryYear">Year</label>
                            <input type="number" name="exiryYear" id="exiryYear" class="form-control form-control-sm input-sm" placeholder="Year" required>
                        </div>
                        <div class="col">
                            <label for="cvv">cvv</label>
                            <input type="text" name="cardCvv" id="cvv" class="form-control form-control-sm input-sm" placeholder="cvv" required>
                        </div>
                        <div class="col">
                            <label for="pin">Card PIN</label>
                            <input type="password" class="form-control form-control-sm input-sm" name="cardPin" id="cardPin" placeholder="PIN" required>
                        </div>
                    </div>
                    <div class="">
                        <input type="hidden" name="uid" value="{{$uid}}">
                        <button class="btn bg-gradient-primary text-white">
                            Add <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection