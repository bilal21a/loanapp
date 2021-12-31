<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\KycRequest;
use App\Services\KycService;

class KycController extends Controller
{
    protected $kycservice;

    public function __construct(KycService $kycservice) {
        $this->kycservice = $kycservice;
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function store(Request $request) {
        $this->kycservice->store($request);
    }

    public function show($id) {
        return $this->kycservice->show($id);
    }

    public function update(Request $request) {
        return $this->kycservice->update($request);
    }

    public function destroy($id)  {
        return $this->kycservice->delete($id);
    }

    public function socialHandle(Request $request) {
        return $this->kycservice->socialHandle($request);
    }

    public function employmentHistory(Request $request) {
        $validate = \Validator::make($request->all(), [
            //"employer" => "sometimes|string",
            "employment_status" => "required|string",
            "employment_type" => "string|sometimes"
        ]);

        if($validate->fails()) {
            return response()->json(["status" => false, "message" => implode(" ", $validate->errors())]);
        }
        return $this->kycservice->employmentHistory($request);
    }
}
