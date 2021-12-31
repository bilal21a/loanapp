@extends('layouts.app')

@section("content")
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div>
        <!-- stats -->
        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Savings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    NGN{{number_format($balance, 2)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{$users}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Investments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    NGN{{number_format($investments, 2)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Auto save today</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    NGN{{number_format($autosavings, 2)}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /stats -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Latest transactions</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>SN</th>
                            <th>Transaction amount</th>
                            <th>Type</th>
                            <th>Transaction Date</th>
{{--                            <th>Short Narration</th>--}}
                            <th>By</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>Transaction amount</th>
                            <th>Type</th>
                            <th>Transaction Date</th>
{{--                            <th>Short Narration</th>--}}
                            <th>By</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{$i++}}</td>
                                <td @if($transaction->type === 'debit') class="text-danger" @endif>{{number_format($transaction->amount, 2)}}</td>
                                <td @if($transaction->type === 'debit') class="text-danger" @endif>{{$transaction->type}}</td>
                                <td>{{$transaction->created_at->toFormattedDateString()}} {{$transaction->created_at->toTimeString()}}</td>
{{--                                <td>{{$transaction->sub_type}}</td>--}}
                                <td> @if($transaction->user !== null){{$transaction->user->last_name." ".$transaction->user->first_name}}@endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
