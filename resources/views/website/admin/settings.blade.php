@extends('layouts.app')

@section('content')
    <div class="container-fluid animated fadeIn slower">

        <div class="col-lg-9 mb-4">
            <h3 class="h4">Settings</h3>
            <p class="text-danger">
                <em> <strong>NOTE:</strong> Make sure to add " " to values where necessary. e.g APP_NAME with 
                whitespace (Mavunifs Savings) should be "Mavunifs savings"
            </em>
            </p>
        </div>

        <div class="col-lg-6 mb-2">
            @include('includes.messages')
        </div>
        <section>
          <div class="card shadow mb-4">
            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
              <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
            </a>
            <div class="collapse show" id="collapseCardExample">
              <div class="card-body">
                <a href="javascript:;" id="addmore" class="btn btn-sm btn-primary btn-icon-split">
                  <span class="text">Add more</span>
                  <span class="icon text-white-50">
                      <i class="fas fa-plus"></i>
                  </span>
                </a>
                <div class="wrapper">
                  <form action="{{route("config.add")}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="settingform">
                      <div class="row" id="settings">
                        <div class="col">
                          <label for="key">Key</label>
                          <input type="text" name="key[]" id="key" placeholder="Enter Key (e.g sitename or paystack)" class="form-control">
                        </div>
                        <div class="col">
                          <label for="value">Key value</label>
                          <input type="text" name="value[]" id="value" placeholder="Enter key value (e.g Mavunifs for sitename or https://paystack.co for paystack API)" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="form-group mt-2">
                      <button class="btn bg-gradient-primary text-white">
                        Save <i class="fas fa-arrow-circle-right"></i>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">APP CONFIGURATION</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <form action="{{route('setting.add')}}" method="POST" enctype="multipart/form-data" class="mb-3 w-100 hidden animated fadeIn" id="editForm">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <select name="keyname" id="keyname" class="form-control" required>
                                    @foreach ($envs as $key => $env)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <input type="text" name="keyvalue" class="form-control" required>
                            </div>
                            <div class="col">
                                <button class="btn bg-gradient-primary text-white">
                                   Save <i class="fas fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div>
                        <a href="javascript:;" id="edit" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="text">Edit</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-edit"></i>
                            </span>
                        </a>
                    </div>
                </div>
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>KEY</th>
                            <th>VALUE</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>KEY</th>
                            <th>VALUE</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($envs as $key =>  $env)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$env}}</td>                                
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
@endsection 
