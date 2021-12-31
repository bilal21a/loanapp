@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="col-lg-6 mb-4">
            <h3 class="h4">Create admin user</h3>
        </div>

        <div class="col-lg-6 col-md-6 col-12">
             @include('includes.messages')
            <div class="wrapper shadow-sm">
                <form action="{{route('signup')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-2">
                        <div class="col">
                            <label for="name">Full Name:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Fullname" required>
                        </div>
                        <div class="col">
                            <label for="gender">Gender:</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="">Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="phonenumber">Phone number</label>
                            <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Phone number" required>
                        </div>
                        <div class="col">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="password">Confirm Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form-control" required>
                        </div>
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