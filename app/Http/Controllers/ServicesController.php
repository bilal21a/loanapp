<?php

namespace App\Http\Controllers;

use App\LoanCategory;
use Illuminate\Http\Request;
use App\Services;
use App\ServiceCategory;
use App\Http\Resources\ServicesResource;
use App\Http\Resources\ServiceCategoryResource;
use App\Http\Resources\BannerResource;
use App\Banner;

class ServicesController extends Controller
{
    protected $service;
    protected $category;
    protected $loan;

    public function __construct(ServiceCategory $category, Services $service, LoanCategory $loan)
    {
        $this->service = $service;
        $this->category = $category;
        $this->loan = $loan;
    }

    public function index()
    {
        $data = $this->service->where('status', 1)->get();
        return  ServicesResource::collection($data);
    }

    public function loans()
    {
        $data = $this->loan->where('status', 1)->get();
        return response()->json(['status' => true, "data" => $data]);
    }

    public function categories()
    {
        $data = $this->category->orderBy('name', 'ASC')->get();
        return  ServiceCategoryResource::collection($data);
    }

    public function getCategoryByType($type)
    {
        $data = $this->category->where('type', $type)->first();
        return  response()->json(['status' => true, "message" => "success", "data" => new  ServiceCategoryResource($data)]);
    }

    public function getDataPlans($vendor)
    {
        $client =  new \GuzzleHttp\Client();
        $url = "https://mobileairtimeng.com/httpapi/get-items?userid=".config("settings.mobileng.userid")."&pass=".config("settings.mobileng.password")."&service=".mb_strtolower($vendor);
        $req =  $client->get($url);
        return  response()->json(["status" => true, "data" => json_decode($req->getBody(), true)]);
    }

    public function getBanners()
    {
        $banners = Banner::where('status', 1)->orderBy('created_at', 'DESC')->get();
        return response()->json([
        'status' => true,
        'message' => 'Success',
        'data'  => BannerResource::collection($banners),
     ]);
    }
}
