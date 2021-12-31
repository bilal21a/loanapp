@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-lg-12 mb-4">
            <h3 class="h4">Transactions</h3>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
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
                          <th>Short Narration</th>
                          <th>By</th>
                      </tr>
                      </thead>
                      <tfoot>
                      <tr>
                          <th>SN</th>
                          <th>Transaction amount</th>
                          <th>Type</th>
                          <th>Transaction Date</th>
                          <th>Short Narration</th>
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
                              <td>{{$transaction->sub_type}}</td>
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
