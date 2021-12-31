<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
  public function update(Request $request)
  {
      // if ($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) {

      //     if (config('settings.site_logo') != null) {
      //         $this->deleteOne(config('settings.site_logo'));
      //     }
      //     $logo = $this->uploadOne($request->file('site_logo'), 'img');
      //     Setting::set('site_logo', $logo);

      // } elseif ($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile)) {

      //     if (config('settings.site_favicon') != null) {
      //         $this->deleteOne(config('settings.site_favicon'));
      //     }
      //     $favicon = $this->uploadOne($request->file('site_favicon'), 'img');
      //     Setting::set('site_favicon', $favicon);

      // } else 
      if($request->has("key") && !empty($request->key)) {
        $keys = array_combine($request->key, $request->value);

        foreach ($keys as $key => $value)  {
          Setting::saveConfig($key, $value);
        }
        
     }
      return back()->withMessage('Settings updated successfully');
  }

  // public function banners(Request $request) {
  //   if($request->has('banner') && !empty($request->banner)) {
  //     foreach ($request->banner as $key => $banner) {
        
  //     }
  //   }
  // }


}
