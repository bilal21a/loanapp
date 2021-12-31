<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CardService;

class CardController extends Controller
{
    protected $cardservice;

    public function __construct(CardService $cardservice) {
        $this->cardservice = $cardservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
         return $this->cardservice->index();
    }

    public function store(Request $request) {
        return $this->cardservice->newCard($request);
    }

    public function show($id) {
        return $this->cardservice->find($id);
    }

    public function update($id, Request $request) {
        return $this->cardservice->update($id, $request);
    }

    public function delete($id) {
        return $this->cardservice->delete($id);
    }
    
    public function setCard($id) {
        return $this->cardservice->setCard($id);
    }
}
