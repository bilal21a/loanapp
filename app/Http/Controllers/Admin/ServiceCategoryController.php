<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ServicesService;

class ServiceCategoryController extends Controller
{
    protected $service;

    public function __construct(ServicesService $service) {
      $this->service = $service;
    }

    public function newServiceProvider(Request $request) {
      return $this->service->newServiceProvider($request);
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
      return $this->service->update($id, $request);
    }

    public function delete($id) {
      return $this->service->delete($id);
    }

    public function newAppSetting(Request $request) {
      return $this->service->newAppSetting($request);
    }

    public function allServiceProviders() {
      return $this->service->allServiceProviders();
    }

    public function appSetting() {
      return $this->service->appSetting();
    }

    public function banners(Request $request) {
      return $this->service->banners($request);
    }

  public function allBanners() {
    return $this->service->allBanners();
  }


  public function updateBanner($id) {
    return $this->service->updateBanner($id);
  }

  public function deleteBanner($id) {
    return $this->service->deleteBanner($id);
  }

//    public function banners(Request $request) {
//      return $this->service->banners($request);
//    }

//  public function allBanners() {
//    return $this->service->allBanners();
//  }


//  public function updateBanner($id) {
//    return $this->service->updateBanner($id);
//  }
//
//  public function deleteBanner($id) {
//    return $this->service->deleteBanner($id);
//  }
}
