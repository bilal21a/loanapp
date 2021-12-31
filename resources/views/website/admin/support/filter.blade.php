@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h3 class="h4">Ticket Filter</h3>
            <p>Support Tickets</p>
        </div>

        <div class="col-lg-6">
            @include('includes.messages')
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Support Tickets Filter</h6>
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
                                                  <form action="{{url("/")."/admin/support/tickets/$ticket->id"}}" method="POST">
                                                      @csrf
                                                      @method("DELETE")
                                                      <button class="button-sm text-danger">
                                                      Delete
                                                      </button>
                                                  </form>
                                              </div>
                                            </li>
                                        </ul>
                                    </nav>
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
