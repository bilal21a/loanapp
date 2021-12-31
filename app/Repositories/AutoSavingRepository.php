<?php
namespace App\Repositories;

use App\AutoSaving;
use App\Http\Resources\AutoSavingResource;
use Carbon\Carbon;

class AutoSavingRepository {

    protected $autosave;

    public function __construct(AutoSaving $autosave) {
        $this->autosave = $autosave;
    }

    public function index() {
        $data = $this->autosave->orderBy("created_at", "desc")->take(100)->paginate(50);
        return AutoSavingResource::collection($data);
    }

    public function create($request) {

        $date =  $this->autosave->dateCalculator($request->prefered_type);
        $exists = $this->autosave->where('user_profile_id', auth()->guard('profile')->user()->id)->first();
        if($exists ) {
            $save = $exists->update([
                "user_profile_id" => auth()->guard("profile")->user()->id,
                "prefered_date" => $date->toDateString(),
                "prefered_time" => $request->prefered_time =="" ? $date->toTimeString() : $request->prefered_time,
                "amount" => trim($request->amount),
                "prefered_type" => $request->prefered_type,
                "next_charge_date" => $date->toDateString(),
                "status" => 1,
            ]);
        } else {
            $save = $this->autosave->create([
                "user_profile_id" => auth()->guard("profile")->user()->id,
                "prefered_date" => $date->toDateString(),
                "prefered_time" => $request->prefered_time =="" ? $date->toTimeString() : $request->prefered_time,
                "amount" => trim($request->amount),
                "prefered_type" => $request->prefered_type,
                "next_charge_date" => $date->toDateString(),
                "status" => 1,
            ]);
        }

        if($save) {
            return response()->json(["status" => true, "data" => new AutoSavingResource( $exists)]);
        }

        return response()->json("An error occured. Try again");
    }

    public function find($id) {
        return $this->autosave->find($id);
    }

    public function update($id, $request) {
        $saving = $this->autosave->find($id);
        if($saving) {
            if($request->has("status") && $request->status !== "") {
                $saving->update(["status" => !$saving->status]);
                return response()->json(["status" => true, "message" => "success"]);
            }

            $date =  $this->autosave->dateCalculator($request->prefered_type);
            $saving->update([
                "prefered_date" => $date->toDateString(),
                "prefered_time" => $request->prefered_time =="" ? $date->toTimeString() : $request->prefered_time,
                "amount" => trim($request->amount),
                "prefered_type" => $request->prefered_type,
                "next_charge_date" => $date->toDateString(),
                ]);

            return new AutoSavingResource($saving);
        }

        return response()->json("Error charging your account.");
    }

    public function delete($id) {
        $saving = $this->autosave->find($id);
        if($saving) {
            $saving->delete();
        }
        return response()->json("Error deleting.");
    }

    public function acivateOrDeacticate($id) {
        $saving = $this->autosave->find($id);
        if($saving->status) {
            $account->update(["status" => 0]);
        }
        else {
            $account->update(["status" => 1]);
        }

        return new AutoSavingResource($saving);
    }
}
