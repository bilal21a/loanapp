@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3">
            @include('includes.messages')

        </div>

        <div class="row">
            <div class="card">
                <div class="card-header">{{$user->last_name}} -  Profile Update</div>
                <div class="card-body shadow-sm animated fadeIn slower">
                    <form action="/admin/users/{{$user->id}}" method="POST" enctype="application/x-www-form-urlencoded">
                        @csrf
                        @method("PUT")
                        <div class="row mb-2">
                            <div class="col">
                                <label for="first_name">First name</label>
                                <input type="text" value="{{$user->first_name}}" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="last_name">Last name</label>
                                <input type="text" value="{{$user->last_name}}" name="last_name" id="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="{{$user->gender}}">{{$user->gender}}</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="city">City</label>
                                <input type="text" value="{{$user->city}}" name="city" id="city" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="email">Email</label>
                                <input type="email" value="{{$user->email}}" name="email" id="email" class="form-control" readonly>
                            </div>
                            <div class="col">
                                <label for="phonenumber">Phone number</label>
                                <input type="text" value="{{$user->phone_number}}" name="phone_number" id="phone_number" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-lg  bg-gradient-primary text-white">
                            <span class="text">Save</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
