<?php
namespace App\Repositories;

use App\Ticket;
use App\Reply;
use App\Http\Resources\TicketResource;
use App\Http\Resources\ReplyResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TicketsRepository {

  protected $ticket;
  protected $reply;

  public function __construct(Ticket $ticket, Reply $reply) {
    $this->ticket = $ticket;
    $this->reply =  $reply;
  }

  public function index($id) {
    $data = $this->ticket->where('profile_id', $id)->orderBy('created_at', 'DESC')->paginate(20);
    return response()->json([
      'status' => true,
      'message' => 'success',
      'data' => TicketResource::collection($data)
    ]);
  }

  public function store($request) {

    $attchment = $request->has('file') ? $this->uploadOne($request->file) : null;

    $data = $this->ticket->create([
      'profile_id' => auth()->user()->id,
      'title' => $request->title,
      'body' => $request->body,
      'attachment' => $attchment
    ]);
    return response()->json([
      'status' => true,
      'message' => 'Your ticket has been submitted successfully',
      'data' => new TicketResource($data)
    ]);
  }

  public function reply($request) {

    $attchment = $request->has('file') ? $this->uploadOne($request->file) : null;
    $data = $this->reply->create([
      'ticket_id' => $request->ticket_id,
      'user_id' => $request->user_id,
      'body' => $request->body,
      'attachment' => $attchment,
    ]);
    if($data) {
      return \response()->json([
        'status' => true,
        'message' => 'Your message has been submitted',
        'data' => new ReplyResource($data)
      ]);
    }

    return response()->json([
      'status' => false,
      'message' => 'Ticket does not exist, contact service provider for assisance'
    ]);
  }


  public function show($id) {
    $data = $this->ticket->find($id);
    return response()->json([
      'status' => true,
      'message' => 'success',
      'data' => new TicketResource($data)
    ]);
  }

  public function ticketFilter($uid, $type) {
    $data = $this->ticket->where('profile_id', $uid)->where('status', $type)->orderBy('created_at', 'DESC')->get();
    return response()->json([
      'status' => true,
      'message' => 'success',
      'data' => TicketResource::collection($data)
    ]);
  }

  public function uploadOne(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
  {
      $name = !is_null($filename) ? $filename : \Str::random(25);

      return $file->storeAs(
          $folder,
          $name . "." . $file->getClientOriginalExtension(),
          $disk
      );
  }

}
