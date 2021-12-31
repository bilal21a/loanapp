<?php
namespace App\Repositories\Admin;

use App\ServiceCategory;
use App\Banner;

class ServicesCategoryRepository {

  protected $servicecategory;

  public function __construct(ServiceCategory $servicecategory) {
    $this->servicecategory = $servicecategory;
  }

  public function newServiceProvider($request) {
    $service_charge = empty($request->service_charge) ? 0 : $request->service_charge;
    $save =  $this->servicecategory->create([
      "name" => $request->name,
      "type" => str_replace(' ','_', strtolower($request->name)),
      "service_charge" => $service_charge,
      "logo" => $this->uploadOne($request),
    ]);
    if($save) {
      return back()->withMessage("Service category created");
    }

    return back()->withErrors("Error creating service category");

  }

  public function newDataProvider($request) {}

  public function create() {
    $data =  $this->servicecategory->where("status", 1)->orderBy("name", "asc")->get();
    return view("website.admin.services.category.new-category")->withCategories($data);
  }

  public function edit($id) {
    $category = $this->servicecategory->find($id);
    $data =  $this->servicecategory->where("status", 1)->orderBy("name", "asc")->get();
    return view("website.admin.services.category.edit")->withCategory($category)->withCategories($data);
  }

  public function update($id, $request) {
    $category = $this->servicecategory->findOrFail($id);

    if($category) {
      $update =  $category->update($request->all());
      if($request->has('logo') && !empty($request->logo)) {
        $category->update([
          "logo" => $this->uploadOne($request)
        ]);
      }
      if($update) {
        return back()->withMessage("Chnages saved");
      }
      return back()->withErrors("Error saving changes");
    }
    return back()->withMessage("Category not found");
  }

  public function show($id) {
    $category = $this->servicecategory->findOrFail($id);
    return view("website.admin.services.category.show")->withCategory($category);
  }

  public function delete($id) {
    $category =  $this->servicecategory->findOrFail($id);

    if($category) {
      $category->delete();
      return back()->withMessage("Service category deleted");
    }
    return back()->withMessage("Service category not found");
  }

  public function newAppSetting($request) {}

  public function allServiceProviders() {
    $servicecategory = $this->servicecategory->orderBy("name", "desc")->get();

    return view("website.admin.services.category.index")->withServiceCategories($servicecategory);
  }

  public function allBanners() {
    $banners = Banner::orderBy("created_at", "desc")->get();

    return view("website.admin.banners")->withBanners($banners);
  }

  public function banners($request) {
    if($request->has('banner') && !empty($request->banner)) {
      $status = $request->status === "yes" ? 1 : 0;
      $banners = $request->file('banner');

      foreach($banners as $file) {
          $filename = time().time().$file->getClientOriginalName();
          $fileFullName = str_replace(' ','_', $filename );
          $target_dir = public_path('/imgs/banners');
          $file->move($target_dir, $fileFullName);

          $banner = new Banner();
          $banner->banner_url = $fileFullName;
          $banner->status = $status;
          $banner->save();
          $message = "Banner uploaded";
      }
      return back()->withMessage($message);
    }
    else {
      return back()->withErrors("Select a banner to upload");
    }
  }

  public function updateBanner($id) {
    $banner = Banner::find($id);
     $update = $banner->update(["status" => !$banner->status]);
     if($update) {
      return back()->withMessage("Banner status updated");
     }
     else {
      return  back()->withErrors("Could not update banner");
     }
  }

  public function deleteBanner($id) {
    $banner = Banner::find($id);
     $update = $banner->delete();
     if($update) {
      return back()->withMessage("Banner deleted");
     }
     else {
      return  back()->withErrors("Could not delete banner");
     }
  }

//  public function allBanners() {
//    $banners = Banner::orderBy("created_at", "desc")->get();
//
//    return view("website.admin.banners")->withBanners($banners);
//  }

//  public function banners($request) {
//    if($request->has('banner') && !empty($request->banner)) {
//      $status = $request->status === "yes" ? 1 : 0;
//      $banners = $request->file('banner');
//
//      foreach($banners as $file) {
//          $filename = time().time().$file->getClientOriginalName();
//          $fileFullName = str_replace(' ','_', $filename );
//          $target_dir = public_path('/imgs/banners');
//          $file->move($target_dir, $fileFullName);
//
//          $banner = new Banner();
//          $banner->banner_url = $fileFullName;
//          $banner->status = $status;
//          $banner->save();
//          $message = "Banner uploaded";
//      }
//      return back()->withMessage($message);
//    }
//    else {
//      return back()->withErrors("Select a banner to upload");
//    }
//  }
//
//  public function updateBanner($id) {
//    $banner = Banner::find($id);
//     $update = $banner->update(["status" => !$banner->status]);
//     if($update) {
//      return back()->withMessage("Banner status updated");
//     }
//     else {
//      return  back()->withErrors("Could not update banner");
//     }
//  }
//
//  public function deleteBanner($id) {
//    $banner = Banner::find($id);
//     $update = $banner->delete();
//     if($update) {
//      return back()->withMessage("Banner deleted");
//     }
//     else {
//      return  back()->withErrors("Could not delete banner");
//     }
//  }

  public function uploadOne($request) {
    if($file = $request->file('logo')) {
        $fileName = time().time().'.'.$request->logo->getClientOriginalExtension();
        $target_dir = public_path('/imgs/logos');

        if($file->move($target_dir, $fileName)) {
            $fileNameToStore = $fileName;
        } else {
            $fileNameToStore = "no-image.jpg";
        }
        return $fileNameToStore;
    }
  }
}
