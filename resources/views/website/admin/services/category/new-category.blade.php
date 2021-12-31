@extends('layouts.app')

@section("content")
  <div class="container-fluid">
    <div class="col-lg-12 mb-4">
      <h3 class="h4">Add Service Category</h3>
    </div>
    <div class="col-lg-6 col-md-6 col-12 animated fadeIn slow">
      @include('includes.messages')
        <div class="wrapper shadow-sm">
            <form action="{{route('category.add')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-2">
                    <div class="col">
                        <label for="name">Service category name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Service name" required>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="code">Has service charge?</label>
                        <select name="code" id="serviceChargeOption" class="form-control" required>
                            <option>--Select--</option>
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="col hidden" id="serviceCharge">
                        <label for="service_charge">Service charge:</label>
                        <input type="number" name="service_charge" id="service_charge" class="form-control" placeholder="Service charge ">
                    </div>
                </div>
                <div class="form-group">
                    <label for="logo" class="btn  bg-gray-200">
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