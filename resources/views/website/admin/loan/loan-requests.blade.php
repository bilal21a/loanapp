@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-3">
            <h3 class="h4">Loans</h3>
            <p>Manage Loans from here.</p>
        </div>
        <div class="col-lg-12 mb-3">
          @include('includes.messages')
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Loans</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        View loans transactions from all users
                    </div>
                </div>
              <div class="table-responsive">
                <table class="table table-boredered table-hover " id="dataTable">
                    <thead>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Due date</th>
                        <th>Approval status</th>
                        <th>Settlement status</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; $j = 1; ?>
                            @foreach ($loans as $loan)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                      {{$loan->user->first_name." ".$loan->user->last_name}}
                                    </td>
                                    <td>{{number_format($loan->amount, 2)}}</td>
                                    <td>{{number_format($loan->interest, 2)}}</td>
                                    <td>
                                      @if (Carbon\Carbon::now()->toDateString() > $loan->due_date)
                                        <span class="text-danger">{{$loan->due_date}}<span>
                                      @else
                                        <span class="text-success">{{$loan->due_date}}<span>
                                      @endif
                                    </td>
                                    <td>
                                      @if ($loan->approval_status === "Declined")
                                          <span class="text-danger">{{$loan->approval_status}}</span>
                                      @else
                                        {{$loan->approval_status}}
                                      @endif
                                    </td>
                                    <td>
                                        @if ($loan->is_settled)
                                          <span class="text-success">Settled</span>
                                        @else
                                          <span class="text-danger">Unsettled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($loan->status)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if ($loan->approval_status === "pending")
                                                <button type="button"
                                                        class="btn btn-primary btn-sm lmgtbtn"
                                                        data-id="{{$loan->id}}" data-type="approve"
                                                        data-toggle="modal"
                                                        data-target="#approvalModal"
                                                        data-url="{{route('loan.update', [$loan->id, 'option' => 'approve'])}}"
                                                >Approve</button>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm lmgtbtn"
                                                        data-id="{{$loan->id}}" data-type="decline"
                                                        data-toggle="modal"
                                                        data-target="#approvalModal"
                                                        data-url="{{route('loan.update', [$loan->id, 'option' => 'decline'])}}"
                                                >Decline</button>
                                            @endif

                                                @if ($loan->approval_status === "approved")
                                                    <button type="button"
                                                            class="btn btn-success btn-sm lmgtbtn"
                                                    >Approved</button>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm lmgtbtn"
                                                            data-id="{{$loan->id}}" data-type="decline"
                                                            data-toggle="modal"
                                                            data-target="#approvalModal"
                                                            data-url="{{route('loan.update', [$loan->id, 'option' => 'decline'])}}"
                                                    >Decline</button>
                                                @endif

                                                @if ($loan->approval_status === "declined")
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm lmgtbtn"
                                                            data-id="{{$loan->id}}" data-type="approve"
                                                            data-toggle="modal"
                                                            data-target="#approvalModal"
                                                            data-url="{{route('loan.update', [$loan->id, 'option' => 'approve'])}}"
                                                    >Approve</button>
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm lmgtbtn"
                                                    >Declined</button>
                                                @endif
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
