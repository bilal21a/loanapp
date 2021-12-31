@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h3 class="h4">Support Tickets</h3>
            <p>Support Tickets</p>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{url('/').'/admin/support'}}">
                <div class="card border-left-info shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total tickets</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{count($tickets)}}
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-headset fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
           </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{url('/').'/admin/support/tickets/pending'}}">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{$pending}}
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-headset fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>

           </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{url('/').'/admin/support/tickets/answered'}}">
                <div class="card border-left-success shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Answered</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{$answered}}
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-comment fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
           </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <a href="{{url('/').'/admin/support/tickets/closed'}}">
                <div class="card border-left-danger shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Closed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{$closed}}
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
           </div>

         </div>
        <div class="col-lg-6">
            @include('includes.messages')
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Support Tickets</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>By</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>By</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @if ($tickets)
                        <?php $i = 1; ?>
                            @foreach($tickets as $ticket)
                                @if($ticket->user)
                                    <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        @if($ticket->user != null)
                                            {{$ticket->user->first_name.' '.$ticket->user->last_name}}
                                        @endif
                                    </td>
                                    <td>{{$ticket->title}}</td>
                                    <td>
                                      {{$ticket->body}}
                                      @if (!empty($ticket->attachment))
                                          <a href="{{asset('storage/'.$ticket->attachment)}}">
                                            <i class="fas fa-paperclip"></i>
                                          </a>
                                      @endif
                                    </td>
                                    <td>
                                      @if ($ticket->status === 'pending')
                                        <span class="text-warning">{{$ticket->status}}</span>
                                      @endif
                                      @if ($ticket->status === 'answered')
                                        <span class="text-primary">{{$ticket->status}}</span>
                                      @endif
                                      @if ($ticket->status === 'closed')
                                        <span class="text-success">{{$ticket->status}}</span>
                                      @endif
                                    </td>
                                    <td>
                                      <nav>
                                        <ul class="nav">
                                            <li class="dropdown no-arrow">
                                              <a href="javascript:;" data-toggle="dropdown">
                                                  <i class="fas fa-ellipsis-h"></i>
                                              </a>
                                              <div class="dropdown-menu text-center dropdown-menu shadow animated">
                                                  <a href="{{url("/")."/admin/support/tickets/$ticket->id"}}">
                                                      Reply
                                                  </a> <br>
                                                  <a href="{{url("/")."/admin/support/tickets/$ticket->id/close"}}">
                                                      Close
                                                  </a> <br>
                                                  <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$ticket->id}}' data-toggle="modal" data-target="#exampleModal">Delete</button>

                                              </div>
                                            </li>
                                        </ul>
                                    </nav>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
@endsection
