<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\CardService;

class CardController extends Controller
{
    protected $cardservice;

    public function __construct(CardService $cardservice) {
        $this->cardservice = $cardservice;
        $this->middleware(["assign.guard", "auth:admin"]);
    }

    public function index() {
         return $this->cardservice->index();
    }

    public function create($id) {
         return $this->cardservice->create($id);
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
}
