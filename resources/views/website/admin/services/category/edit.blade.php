@extends('layouts.app')

@section("content")
  <div class="container-fluid">
    <div class="col-lg-12 mb-4">
      <h3 class="h4">Edit {{$category->name}} Service Category</h3>
    </div>
    <div class="col-lg-6 col-md-6 col-12 animated fadeIn slow">
      @include('includes.messages')
        <div class="wrapper shadow-sm">
            <form action="{{url("/")."/admin/services/category/$category->id"}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row mb-2">
                    <div class="col">
                        <label for="name">Service category name:</label>
                        <input type="text" name="name" id="name" value="{{$category->name}}" class="form-control" placeholder="Service name" required>
                    </div> 
                    <div class="col">
                        <label for="code">Service category code:</label>
                        <input type="text" name="code" id="code" value="{{$category->code}}" class="form-control" placeholder="Service code ">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="service_charge">Service charge</label>
                        <input type="number" name="service_charge" id="service_charge" value="{{$category->service_charge}}" class="form-control">
                    </div>
                    <div class="col">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            @if ($category->status == 1)
                                <option value="1">Active</option>
                                <option value="0">Deactivate</option>
                            @else
                                <option value="0">Inactive</option>
                                <option value="1">Activate</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <img src="{{asset('imgs/logos/'.$category->logo)}}" width="50" alt="logo">
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