<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AutoSaving extends Model
{
    protected $table = "auto_saving";

    protected $fillable = [
        "user_profile_id",
        "prefered_date",
        "prefered_time",
        "next_charge_date",
        "amount",
        "prefered_type",
        "status"
    ];

    public function user() {
      return $this->hasMany(Profile::class, "id");
    }

    public function userAutoSaveSetting($user_id) {
      return self::findOrFail($user_id);
    }

    public function dateCalculator($interval) {
      $today = new Carbon; 

      switch ($interval) {
        case 'daily':
          $chargedate = Carbon::parse($today)->addDays(1);
          break;

        case 'weekly':
          $chargedate = Carbon::parse($today)->addDays(7);
          break;

        case 'monthly':
          $chargedate = Carbon::parse($today)->addDays(30);
          break;
        
        default:
          $chargedate =  Carbon::parse($today);
          break;
      }
      
      return $chargedate;
    }

}
