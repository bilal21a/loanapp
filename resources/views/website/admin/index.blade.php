@extends('layouts.header')

@section('content')
    <div class="container-fluid ">
        <div class="row justify-content-center  align-items-center" style="height: 100vh !important;">
            <div class="col-lg-4 col-md-4 col-12 login bg-white shadow-lg animated pulse slower">
                <div class="inner">
                    @include('includes.messages')
                    <h3>Login</h3>
                    <form action="{{route('admin.login')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-lg" required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-lg form-control">Login</button>
                        </div>
                    </form>
                    <div class="form-footer text-center">
                        <a href="{{route('admin.password.request')}}">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection