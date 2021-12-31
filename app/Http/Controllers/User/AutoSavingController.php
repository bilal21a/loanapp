<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AutoSavingService;
use App\Repositories\AutoSavingRepository;

class AutoSavingController extends Controller
{
    protected $autosaveservice;
    protected  $autoSavingRepository;

    public function __construct(AutoSavingService $autosaveservice, AutoSavingRepository $autoSavingRepository) {
        $this->autosaveservice = $autosaveservice;
        $this->autoSavingRepository = $autoSavingRepository;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
        return $this->autosaveservice->index();
    }

    public function store(Request $request) {
        return $this->autoSavingRepository->create($request);
    }

    public function show($id) {
        return $this->autosaveservice->find($id);
    }

    public function update($id, Request $request) {
        return $this->autosaveservice->update($id, $request);
    }

    public function delete($id) {
        return $this->autosaveservice->delete($id);
    }

    public function toggleStatus($id) {
        return $this->autosaveservice->toggleStatus($id);
    }
}
