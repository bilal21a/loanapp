<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\ServiceRepository;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(ServiceRepository $service) {
      $this->service = $service;
    }
  
    public function newServiceProvider(Request $request) {
      return $this->service->store($request);
    }

    public function create() {
      return $this->service->create();
    }

    public function edit($id) {
      return $this->service->edit($id);
    }

    public function show($id) {
      return $this->service->show($id);
    }

    public function update($id, Request $request) {
      return $this->service->update($id, $request->all());
    } 

    public function delete($id) {
      return $this->service->delete($id);
    }
  
    public function allServices() {
      return $this->service->allServices();
    }
    
    public function appSetting() {
      return $this->service->appSetting();
    }

}
