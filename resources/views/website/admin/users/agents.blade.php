@extends('layouts.app')

@section("content")
    <div class="container-fluid">

        <div class="mb-4">
            <h1 class="h3 mb-0 text-gray-800">Agents Profiles</h1>
        </div>
        <div class="col-lg-12">
            @include('includes.messages')
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Agents Profiles</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url("/")."/admin/users/new-user"}}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="text">Add</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTable">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Verified</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone number</th>
                            <th>Verified</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($users as $user)
                            @if($user->user_type === 'agent')
                                <tr>
                                <td>{{$i++}}</td>
                                <td>{{$user->first_name." ".$user->last_name}}</td>
                                <td>{{$user->gender}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone_number}}</td>
                                <td>
                                    @if ($user->isVerified)
                                        <span class="text-success">Verified</span>
                                    @else
                                        <span class="text-danger">Not verified</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="text-success">Active</span>
                                    @else
                                        <span class="text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{url("/")."/admin/users/$user->id"}}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{url("/")."/admin/users/$user->id/edit"}}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$user->id}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
