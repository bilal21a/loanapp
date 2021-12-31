<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Http\Resources\TransactionResource;

class TransactionsController extends Controller
{

    public function  __construct()
    {
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function index() {
      $data = Transaction::orderBy('created_at', 'desc')->get();
         return response()->json(['status' => true, 'message' => 'success', 'data' => TransactionResource::collection($data)]);
    }

    public function show($id) {
      $data = Transaction::find($id);
      if($data) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => new TransactionResource($data)]);
      }
      return response()->json(['status' => false, 'message' => 'No record Found']);
    }

    public function getByType($type) {
      $data = Transaction::where('type', $type)->orderBy('created_at', 'desc')->get();
      if($data) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => TransactionResource::collection($data)]);
      }
      return response()->json(['status' => false, 'message' => 'No record Found']);
    }

    public function getBySubType($type) {
      $data = Transaction::where('type', $type)->orderBy('created_at', 'desc')->get();
      if($data) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => TransactionResource::collection($data)]);
      }
      return response()->json(['status' => false, 'message' => 'No record Found']);
    }

    public function getByVendor($vendor) {
      $data = Transaction::where('vendor', $vendor)->orderBy('created_at', 'desc')->get();
      if($data) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => TransactionResource::collection($data)]);
      }
      return response()->json(['status' => false, 'message' => 'No record Found']);
    }

    public function getByUser($userid) {
      $data = Transaction::where('user_profile_id', $userid)->orderBy('created_at', 'desc')->get();
      if($data) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => TransactionResource::collection($data)]);
      }
      return response()->json(['status' => false, 'message' => 'No record Found']);
    }
}
