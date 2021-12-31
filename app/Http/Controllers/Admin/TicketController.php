<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\TicketRepository;

class TicketController extends Controller
{
    protected $ticket;

    /** Creat controller instance */
    public function __construct(TicketRepository $ticket) {
        $this->ticket = $ticket;
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function index()
    {
        $data = $this->ticket->index();
        $pending = $this->ticket->pendingCount();
        $answered = $this->ticket->answeredCount();
        $closed = $this->ticket->closedCount();
        return view('website.admin.support.index')->withTickets($data)
                ->withPending($pending)->withAnswered($answered)->withClosed($closed);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('website.admin.support.reply');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       return $this->ticket->store($request);
    }

    public function reply(Request $request)
    {
       if($this->ticket->reply($request)) {
           return back()->withMessage('Sent');
       }

       return back()->withErrors('Cannot send reply');
    }

    public function ticketFilter($type) {
        $data = $this->ticket->ticketFilter($type);

        return view('website.admin.support.filter')->withTickets($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->ticket->show($id);
        return view('website.admin.support.show')->withTicket($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->ticket->show($id);
        return view('website.admin.support.edit')->withTicket($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($thi->ticket->update($request, $id)) {
            return back()->withMessage('Ticket updated successfully');
        }

        return back()->withErrors('Cannot update ticket');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->ticket->delete($id)) {
            return back()->withMessage('Ticket deleted successfully');
        }

        return back()->withErrors('Cannot delete ticket');
    }

    public function close($id) {
        if($this->ticket->close($id)) {
            return back()->withMessage('Ticket closed');
        }

        return back()->withErrors('Cannot close ticket');
    }
}
