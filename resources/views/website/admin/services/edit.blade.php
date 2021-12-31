@extends('layouts.app')

@section("content")
  <div class="container-fluid">
    <div class="col-lg-12 mb-4">
      <h3 class="h4">Edit {{$service->name}} Service</h3>
    </div>
    <div class="col-lg-6 col-md-6 col-12 animated fadeIn slow">
      @include('includes.messages')
        <div class="wrapper shadow-sm">
            <form action="{{url("/")."/admin/services/$service->id"}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row mb-2">
                    <div class="col">
                        <label for="name">Service name:</label>
                        <input type="text" name="name" id="name" value="{{$service->name}}" class="form-control" placeholder="Service name" required>
                    </div>
                    <div class="col">
                        <label for="code">Service code</label>
                        <input type="text" name="code" id="code" value="{{$service->code}}" class="form-control" placeholder="Service code ">
                    </div>
                </div>
                <div class="form-group">
                    <label for="categorytype">Service Category</label>
                    <select name="category_id"  class="form-control">
                        <option value="{{$service->category->id}}">{{$service->category->name}}</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row mb-2">
                  <div class="col">
                    <label for="amount">Amount</label>
                    <input type="text" name="amount" placeholder="Amount" value="{{$service->amount}}" class="form-control">
                  </div>
                  <div class="col">
                    <label for="discount">Discount</label>
                    <input type="text" name="discount" placeholder="Discount" value="{{$service->discount}}" class="form-control">
                  </div>
              </div>
              <div class="col-md-12 mb-2">
                  <label for="status">Status</label>
                  <select name="status" id="status" class="form-control">
                      @if ($service->status == 1)
                          <option value="1">Active</option>
                          <option value="0">Deactivate</option>
                      @else
                          <option value="0">Inactive</option>
                          <option value="1">Activate</option>
                      @endif
                  </select>
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