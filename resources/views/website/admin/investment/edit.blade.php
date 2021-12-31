@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        
        <div class="mb-3 col-lg-12">
            <h3 class="h4">Update investment plan</h3>
        </div>

        <div class="col-lg-6 col-md-6 col-12">
            @include('includes.messages')
           <div class="wrapper shadow-sm">
               <form action="/admin/investment/{{$investment->id}}" method="POST" enctype="application/x-www-form-urlencoded">
                   @csrf
                   @method("PUT")
                   <div class="row mb-2">
                       <div class="col">
                           <label for="name">Investment name</label>
                           <input type="text" value="{{$investment->name}}" name="name" id="name" class="form-control" required>
                       </div>
                       <div class="col">
                           <label for="type">Type</label>
                           <input type="text" value="{{$investment->type}}" name="type" id="type" class="form-control"  required>
                       </div>
                   </div>
                   <div class="row mb-2">
                       <div class="col">
                           <label for="amount">Amount:</label>
                           <input type="text" name="amount" value="{{$investment->amount}}" class="form-control" required>
                       </div>
                       <div class="col">
                           <label for="amount_per_slot">Amount per investor:</label>
                           <input type="text" name="amount_per_slot" value="{{$investment->amount_per_investor}}" class="form-control" required>
                       </div>
                   </div>
                   <div class="row mb-2">
                       <div class="col">
                           <label for="duration">Duration:</label>
                           <input type="text" name="duration" value="{{$investment->duration}}" class="form-control" required>
                       </div>
                       <div class="col">
                           <label for="interest_rate">Interest rate:</label>
                           <input type="text" name="interest_rate" value="{{$investment->interest_rate}}" placeholder="Investment interest rate" class="form-control" required>
                       </div>
                   </div>
                   <div class="form-group">
                       <label for="description">Description:</label>
                       <textarea name="description" rows="5" class="form-control" placeholder="Description">{{$investment->description}}</textarea>
                   </div>
                   <div class="form-group">
                       <label for="status">Status:</label>
                       <select name="status" id="status" class="form-control">
                           @if ($investment->status)
                               <option value="{{$investment->status}}">
                                   <span class="text-success">Active<span>
                               </option>
                               <option value="0">
                                   <span class="text-danger">Deactivate<span>
                               </option>
                           @else
                               <option value="{{$investment->status}}">
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
           </div>
       </div>
    </div>
@endsection