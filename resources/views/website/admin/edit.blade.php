@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="col-lg-7 mb-4">
            <h3 class="h4">Update profile</h3>
        </div>

        <div class="col-lg-6">
            @include('includes.messages')
        </div>

        <div class="col-lg-6 col-md-6 col-12">
            <div class="wrapper shadow-sm">
                <form action="{{url("/")."/admin/$user->id"}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" value="{{$user->name}}" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="{{$user->gender}}">{{$user->gender}}</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phonenumber">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{$user->phone_number}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}" readonly>
                    </div> 
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="is_active" id="status" class="form-control">
                            @if ($user->is_active)
                                <option value="{{$user->is_active}}">Active</option>
                                <option value="0">Deactivate</option>
                            @else
                                <option value="{{$user->is_active}}">Inactive</option>
                                <option value="1">Activate</option>                            
                            @endif
                        </select>
                    </div>
                    <div class="">
                        <button class="btn text-white btn-lg bg-gradient-primary">
                            Save <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection