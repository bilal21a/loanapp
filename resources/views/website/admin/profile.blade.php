@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="col-lg-7 mb-4">
            <h3 class="h4">Admin profile</h3>
        </div>

        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-sm-flex align-items-center justify-content-between">
                       <h6 class="m-0 font-weight-bold text-primary p-0">Profile</h6>
                       <a href="/admin/{{$user->id}}/edit" >
                           <i class="fas fa-edit"></i>
                       </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                      <div class="">
                        Name: <span>{{$user->name}}</span>
                      </div>
                      <div class="">
                        Gender: <span>{{$user->gender}}</span>
                      </div>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                      <div class="">
                        Email: <span>{{$user->email}}</span>
                      </div>
                      <div class="">
                        Mobile: <span>{{$user->phone_number}}</span>
                      </div>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                      <div class="">
                            Status: 
                            @if ($user->is_active)
                                <span class="text-success">Active</span>
                            @else
                                <span class="text-danger">Inactive</span>
                            @endif  
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection