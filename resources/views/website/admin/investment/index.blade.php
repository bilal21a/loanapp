@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">PARTNERS</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url("/")."/admin/investment/new-investment"}}" class="btn text-white btn-sm bg-gradient-primary">
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
                        <th>Amount (NGN)</th>
                        <th>Total Investment </th>
                        <th>Slot rate </th>
                        <th>Duration</th>
                        <th>Interest rate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; $j = 1; ?>
                            @foreach ($investments as $investment)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        <img src="{{asset("/storage/".$investment->cover_photo)}}" alt="cover_photo" width="50">
                                        {{$investment->name}}
                                    </td>
                                    <td>{{$investment->type}}</td>
                                    <td>{{number_format($investment->amount, 2)}}</td>
                                    <td>{{number_format($investment->total_investment, 2)}}</td>
                                    <td>{{number_format($investment->amount_per_investor, 2)}}</td>
                                    <td>{{$investment->duration}}</td>
                                    <td>{{$investment->interest_rate}}%</td>
                                    <td>
                                        @if ($investment->total_investment == $investment->amount)
                                            <span class="text-danger">Sold out</span>
                                        @else
                                        <span class="text-success">Available</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{url("/")."/admin/investment/$investment->id/investors"}}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-users"></i>
                                            </a>
                                            <a href="{{url("/")."/admin/investment/$investment->id/edit"}}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                          <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$investment->id}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></button>

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
