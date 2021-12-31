<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AdminService;

class AdminController extends Controller
{
    protected $adminservice;

    public function __construct(AdminService $adminservice) {
        $this->adminservice = $adminservice;
        $this->middleware(["assign.guard", "auth:admin"])->except("index");
    }

    public function index() {
        return $this->adminservice->index();
    }

    public function users() {
        return $this->adminservice->users();
    }

    public function create() {
        return view("website.admin.new-form");
    }

    public function store(Request $request) {
        return $this->adminservice->store($request);
    }

    public function show($id) {
        return $this->adminservice->find($id);
    }

    public function edit($id) {
        return $this->adminservice->edit($id);
    }

    public function update($id, Request $request) {
        return $this->adminservice->update($id, $request);
    }

    public function delete($id) {
        return $this->adminservice->delete($id);
    }

    public function dashboard() {
        return $this->adminservice->dashboard();
    }

    public function settings() {
        return $this->adminservice->settings();
    }

    public function saveSettings(Request $request) {
        return $this->adminservice->saveSettings($request);
    }

}
