@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="h4 mb-4">Create New Profile</h3>
        </div>

        <div class="row">
            <div class="col-lg-7">
                @include('includes.messages')
                <div class="wrapper shadow-sm animated fadeIn slower">
                    <form action="{{route("profile.register")}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-2">
                            <div class="col">
                                <label for="first_name">First name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First name" required>
                            </div>
                            <div class="col">
                                <label for="last_name">Last name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last name" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">---Gender---</option>
                                    <option value="Female">Female</option>
                                    <option value="male">Male</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="City">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email address" required>
                            </div>
                            <div class="col">
                                <label for="phonenumber">Phone number</label>
                                <input type="text" name="phone_number" id="phone_number" placeholder="Phone number" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="password_confirmation">Confirm password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" class="form-control" required>
                            </div>
                        </div>
                        <button class="btn bg-gradient-primary btn-lg text-white">
                            <span class="text">Submit</span>
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