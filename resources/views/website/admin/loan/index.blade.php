@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-3">
            <h3 class="h4">Loan categories</h3>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Loans categories</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url("/")."/admin/loan/new-loan"}}" class="btn text-white btn-sm bg-gradient-primary">
                            <span class="text">Add</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                        </a>
                    </div>
                </div>
              <div class="table-responsive">
                <table class="table table-boredered table-hover " id="dataTable">
                    <thead>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Interest rate</th>
                        <th>Interest type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; $j = 1; ?>
                            @foreach ($loans as $loan)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                      {{$loan->name}}
                                    </td>
                                    <td>{{$loan->type}}</td>
                                    <td>{{$loan->interest_rate}}%</td>
                                    <td>{{$loan->interest_type}}</td>
                                    <td>
                                        @if ($loan->status)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{url("/")."/admin/loan/$loan->id/loanees"}}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <a href="{{url("/")."/admin/loan/$loan->id/edit"}}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                          <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$loan->id}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
@endsection
