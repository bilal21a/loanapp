<?php 
namespace App\Repositories\Admin;

use App\Ticket;
use App\Reply;

class TicketRepository {

  protected $ticket;
  protected $reply;

  public function __construct(Ticket $ticket, Reply $reply) {
    $this->ticket = $ticket;
    $this->reply =  $reply;    
  }

  public function index() {
    return $this->ticket->orderBy('created_at', 'DESC')->paginate(20);
  }

  public function show($id) {
    return $this->ticket->findorFail($id);
  }

  public function update($request, $id) {
   $data = $this->ticket->find($id);
    return $data->update([$request->all()]);
  }

  public function delete($id) {
    $data = $this->ticket->find($id);
    $data->replies()->delete(); 
    return $data->delete();
  }

  public function ticketFilter($type) {
    return $this->ticket->where('status', $type)->orderBy('created_at', 'DESC')->get();
  }

  public function close($id) {
    $data =  $this->ticket->find($id);
   return $data->update(['status' => 'closed']);
  }

  public function reply($request) {

    $attchment = $request->has('file') ? $this->uploadOne($request->file) : null;
    $this->ticket->find($request->ticket_id)->update(['status' => 'answered']);
    return $this->reply->create([
              'ticket_id' => $request->ticket_id,
              'user_id' => auth()->guard('admin')->user()->id,
              'body' => $request->body,
              'attachment' => $attchment,
              'status' => 1
            ]);
  }

  public function pendingCount() {
    return $this->ticket->where('status', 'pending')->count();
  }

  public function answeredCount() {
    return $this->ticket->where('status', 'answered')->count();
  }

  public function closedCount() {
    return $this->ticket->where('status', 'closed')->count();
  }
}