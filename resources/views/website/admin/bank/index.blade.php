@extends('layouts.app')

@section("content")
    <div class="container-fluid">
        <div class="col-lg-12 mb-4">
            <h3 class="h4">Users bank</h3>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Users Banks</h6>
            </div>
            <div class="col-lg-8">
                @include('includes.messages')
            </div>
            <div class="card-body">
              <div class="table-responsive">               
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>SN</th>
                              <th>User</th>
                              <th>Bank</th>
                              <th>Account number</th>
                              <th>Bank code</th>
                              <th>Date added</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tfoot>
                          <tr>
                              <th>SN</th>
                              <th>User</th>
                              <th>Bank</th>
                              <th>Account number</th>
                              <th>Bank code</th>
                              <th>Date added</th>
                              <th>Action</th>
                          </tr>
                      </tfoot>
                      <tbody>
                          <?php $i = 1; ?>
                          @foreach ($banks as $bank)
                              @foreach($bank->banks as $account)
                                  <tr>
                                      <td>{{$i++}}</td>
                                      <td>{{$bank->first_name}}</td>
                                      <td>{{$account->bank_name}}</td>
                                      <td>{{$account->account_number}}</td>
                                      <td>{{$account->bank_code}}</td>
                                      <td>{{$account->created_at}}</td>
                                      <td>
                                          <div class="row">
                                            <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$account->id}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></button>
                                          </div>
                                      </td>
                                  </tr>
                              @endforeach
                          @endforeach
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
@endsection