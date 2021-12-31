@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h3 class="h4">Admin users</h3>
        </div>

        <div class="col-lg-6">
            @include('includes.messages')
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">User Profiles</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url('/').'/admin/users/new-user'}}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="text">Add</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                        </a>
                    </div>
                </div>
              <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if ($users)
                        <?php $i = 1; ?>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->gender}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone_number}}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{url("/")."/admin/$user->id"}}" class="mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{url("/")."/admin/$user->id/edit"}}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{url("/")."/admin/$user->id"}}" method="POST">
                                                @csrf
                                                @method("DELETE")
                                                <button class="button-sm text-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
@endsection