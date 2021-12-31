<?php
namespace App\Services\Admin;

use App\Repositories\Admin\ServicesCategoryRepository;

class ServicesService
{
    protected $serviceRepo;

    public function __construct(ServicesCategoryRepository $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }

    public function newServiceProvider($request)
    {
        return $this->serviceRepo->newServiceProvider($request);
    }

    public function create()
    {
        return $this->serviceRepo->create();
    }

    public function edit($id)
    {
        return $this->serviceRepo->edit($id);
    }

    public function show($id)
    {
        return $this->serviceRepo->show($id);
    }

    public function update($id, $request)
    {
        return $this->serviceRepo->update($id, $request);
    }

    public function delete($id)
    {
        return $this->serviceRepo->delete($id);
    }

    public function newAppSetting($request)
    {
        return $this->serviceRepo->newAppSetting($request);
    }

    public function allServiceProviders()
    {
        return $this->serviceRepo->allServiceProviders();
    }

    public function appSetting()
    {
        return $this->serviceRepo->appSetting();
    }
  

    public function banners($request)
    {
        return $this->serviceRepo->banners($request);
    }

    public function allBanners()
    {
        return $this->serviceRepo->allBanners();
    }

    public function updateBanner($id)
    {
        return $this->serviceRepo->updateBanner($id);
    }

    public function deleteBanner($id)
    {
        return $this->serviceRepo->deleteBanner($id);
    }
}
