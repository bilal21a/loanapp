@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="col-lg-7 mb-4">
            <h3 class="h4">{{$ticket->title}}</h3>
        </div>
        <div class="col-lg-6">
          @include('includes.messages')
        </div>
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-sm-flex align-items-center justify-content-between">
                       <h6 class="m-0 font-weight-bold text-primary p-0">{{$ticket->title}}</h6>
                        <h6 class=" font-weight-bold">
                          Status - 
                          @if ($ticket->status === 'pending')
                            <span class="text-warning">{{$ticket->status}}</span>
                          @endif
                          @if ($ticket->status === 'closed')
                            <span class="text-primary">{{$ticket->status}}</span>
                          @endif
                          @if ($ticket->status === 'answered')
                            <span class="text-info">{{$ticket->status}}</span>
                          @endif
                        </h6>
                      </div>
                </div>
                <div class="card-body">
                  <div>
                    <p>{{$ticket->body}} </p>
                    @if (!empty($ticket->attachment))
                        <img src="{{asset('storage/'.$ticket->attachment)}}" width="200" alt="file">
                    @endif
                  </div>
                  <div class="replies">
                    @foreach ($ticket->replies as $reply)
                      @php
                          if($reply->user_id === NULL) {
                            $style = 'user-reply';
                            $icon = 'fas fa-comments';
                            $name = $ticket->user->first_name;
                          }  else {
                            $style = '';
                            $icon = 'fas fa-headset';
                            $name = 'Admin';
                          }
                      @endphp
                      <div class="mt-2 mb-2 d-flex reply {{$style}}">
                        <div class="w-20 box-sm">
                          <i class="{{$icon}}"></i> {{$name}} <br>
                          {{$reply->created_at->toTimeString()}}
                        </div>
                        <div class="w-75 body">
                          {{$reply->body}}
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <div class="form mt-4" id="replyForm">
                    <form action="{{route('reply.store')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group">
                        <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
                        <label for="body">Message:</label>
                        <textarea required name="body" id="body" placeholder="Reply" class="form-control"></textarea>
                        <label class=" btn btn-gray pull-right">
                          <i class="fas fa-paperclip"></i>
                          Attach file
                          <input type="file" name="file" id="file" hidden>
                        </label>
                      </div> 
                      <div class="btn-group">
                        <a href="{{url('/').'/admin/support/tickets/'.$ticket->id.'/close'}}" class="btn btn-danger mr-2">
                          Close Ticket
                        </a>
                        <button class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection