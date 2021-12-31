<?php 
namespace App\Repositories\Admin;

use App\Services;
use App\ServiceCategory;

class ServiceRepository {

  protected $services;

  public function __construct(Services $services) {
    $this->services = $services;
  }

  public function store($request) {
    $save =  $this->services->create([
      "name" => $request->name,
      "code" => $request->code,
      "category_id" => $request->category_id,
      "discount" => $request->discount,
      "amount" => $request->amount,
      "logo" => "no-image.jpg",
      'status' => 1
    ]);
    if($save) {
      return back()->withMessage("Service created");
    }

    return back()->withErrors("Error creating service");

  }

  public function create() {
    $data =  ServiceCategory::orderBy('name', 'ASC')->get();
    return view("website.admin.services.new-service")->withCategories($data);
  }

  public function edit($id) {
    $service = $this->services->find($id);
    $data =  ServiceCategory::where('status', 1)->get();
    return view("website.admin.services.edit")->withService($service)->withCategories($data);
  }

  public function update($id, array $request) {
    $service = $this->services->findOrFail($id);

    if($service) {
      $update =  $service->update($request);
      if($update) {
        return back()->withMessage("Changes saved");
      }
      return back()->withErrors("Error saving changes");
    }
    return back()->withMessage("Service not found");
  }

  public function show($id) {
    $service = $this->services->findOrFail($id);
    return view("website.admin.services.show")->withService($service);
  }
 
  public function delete($id) {
    $service =  $this->services->findOrFail($id);

    if($service) {
      $service->delete();
      return back()->withMessage("Service deleted");
    }
    return back()->withMessage("Service not found");
  }

  public function allServices() {
    $services = $this->services->orderBy("name", "desc")->get();

    return view("website.admin.services.index")->withServices($services);
  }

  public function imageUpload($request) {
    if($file = $request->file('logo')) {
        $fileName = time().time().'.'.$request->logo->getClientOriginalExtension();
        $target_dir = public_path('/imgs/logos');

        if($file->move($target_dir, $fileName)) {
            $fileNameToStore = ["logo" => $fileName];
        } else {
            $fileNameToStore = ["logo" => "no-image.jpg"];
        }
        return $fileNameToStore;
    }
  }
}