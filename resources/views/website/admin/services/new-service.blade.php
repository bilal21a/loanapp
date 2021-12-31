@extends('layouts.app')

@section("content")
  <div class="container-fluid">
    <div class="col-lg-12 mb-4">
      <h3 class="h4">Add Service</h3>
    </div>
    <div class="col-lg-6 col-md-6 col-12 animated fadeIn slow">
      @include('includes.messages')
        <div class="wrapper shadow-sm">
            <form action="{{route('service.add')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-2">
                    <div class="col">
                        <label for="name">Service name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Service name" required>
                    </div>
                    <div class="col">
                        <label for="code">Service code</label>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Service code ">
                    </div>
                </div>
                <div class="form-group">
                    <label for="categorytype">Service Category</label>
                    <select name="category_id"  class="form-control">
                        <option value="">---Select category---</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="discount">Amount (SME data only)</label>
                        <input type="text" name="amount" placeholder="Amount" class="form-control">
                    </div>
                    <div class="col">
                        <label for="discount">Discount</label>
                        <input type="text" name="discount" placeholder="Discount" class="form-control">
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label for="logo" class="btn  bg-gray-200 mt-4">
                        Browse logo ...
                        <input type="file" name="logo" id="logo" hidden>
                      </label>
                </div>
                <div class="">
                    <button class="btn btn-lg text-white bg-gradient-primary">
                        Submit <i class="fas fa-arrow-circle-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
  </div>
@endsection