<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\RefererRepository;

class RefererController extends Controller
{
    protected $refererRepo;

    public function __construct(RefererRepository $refererRepo)
    {
        $this->refererRepo = $refererRepo;
    }

    public function index() {
        return $this->refererRepo->index();
    }

    public function store(Request $request) {
        return $this->refererRepo->store($request);
    }

    public function manage($id) {
        return $this->refererRepo->manage($id);
    }
}
