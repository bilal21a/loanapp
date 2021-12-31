<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentCategoryResource;
use App\Http\Resources\InvestmentResource;
use App\Http\Resources\LoanResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TransactionResource;
use App\LoanCategory;
use App\Ticket;
use App\Transaction;
use App\UserKyc;
use Illuminate\Http\Request;
use App\Loan;
use App\Withdrawal;
use App\AutoSaving;
use App\Investment;
use App\UserInvestment;
use App\Referer;
use App\Http\Resources\SettingResource;
use App\Http\Resources\RefererResource;
use Spatie\Geocoder\Facades\Geocoder;
use App\AppSetting;

class SettingsController extends Controller
{
    public function  __construct()
    {
        $this->middleware(["assign.guard:profile", "jwt.auth"]);
    }

    public function settings() {
        if(auth()->guard('profile')->check()) {
            $user = auth()->guard('profile')->user();
            $withdrawal = Withdrawal::where('user_profile_id', $user->id)->first();
            $transactions = Transaction::where('user_profile_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $investments = Investment::where('status', 1)->get();
            $loans = LoanCategory::where('status', 1)->get();
            $user_loans = Loan::where('user_profile_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $user_investments = UserInvestment::where('user_profile_id', $user->id)->orderBy('created_at', 'DESC')->get();
            $referees = Referer::where('referer_id', $user->id)->get();
            //$points = $user->kycs->sum('points') + $user->social_links()->first()->points + $user->employment->points;
            $tickets = Ticket::where('profile_id', $user->id)->orderBy('created_at', 'DESC')->get();
            if($withdrawal) {
                $widthrawal_day = $withdrawal->withdrawal_day;
            }
            else {
                $widthrawal_day = null;
            }
            $data = (object) array(
                "loans" => $loans,
                "investments" => InvestmentCategoryResource::collection($investments),
                "user_investments" => InvestmentResource::collection($user_investments),
                "user_loans" =>  LoanResource::collection($user_loans),
                "transactions" => TransactionResource::collection($transactions),
                "withdrawal_day" =>  $widthrawal_day,
                "autosave" => AutoSaving::where('user_profile_id', $user->id)->first(),
                "referees" => RefererResource::collection($referees),
                "tickets" => TicketResource::collection($tickets),
                "points" => $user->kycs->sum('points') + ($user->agentApproval ? $user->agentApproval->points : 0) + ($user->employment ? $user->employment->points : 0) + ($user->social_links()  && count($user->social_links) > 0 ? $user->social_links()->first()->points : 0),
            );

            return response()->json(["status" => true, "message" => "success" , "data" => new SettingResource($data)], 200);
        }

        return response()->json(["status" => false, "message" => "Unauthorized"], 401);
    }

    public function getSettings($id) {
        $setting = AppSetting::where('user_profile_id', $id)->get();
        return response()->json(['status' => true, 'data' => $setting]);
    }

    public  function notificationSettings(Request $request) {
        $settings = AppSetting::where('user_profile_id', $request->user_id)->where('name', $request->name)->first();
        if($settings) {
            //$setting = AppSetting::where('name', $request->name)->first();
            $update = $settings->update(['status' => !$settings->status]);
            if($update) {
                return response()->json(['status' => true, 'message' => 'success', 'data' => new ProfileResource($settings->user)]);
            }
            return response()->json(['status' => false, 'message' => 'Could not save settings', 'data' => []]);
        }
       $setting = AppSetting::create([
           'user_profile_id' => $request->user_id,
            'name' => $request->name,
            'status' => true,
        ]);
        if($setting) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new ProfileResource($setting->user)]);
        }

        return response()->json(['status' => false, 'message' => 'Failed', 'data' => []]);

    }

    public function getLocation($longitude, $latitude) {
        $location = Geocoder::getAddressForCoordinates($longitude, $latitude);
        return response()->json(['status' => true, 'data' => $location]);
    }
}
