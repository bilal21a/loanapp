<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserKyc extends Model
{
    protected $table = 'user_kyc';
    protected $fillable = [
        'type', 'value', 'user_profile_id', 'status', 'approval_status', 'reason_for_approval',
        'reason_for_disapproval', 'last_location', 'points'
    ];

    public function profile() {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }

    /**
     * @param $key
     */
    public static function get($type)
    {
        $kyc = new self();
        $entry = $kyc->where('type', $type)->first();
        if (!$entry) {
            return;
        }
        return $entry->value;
    }

    /**
     * @param $key
     * @param null $value
     * @return bool
     */
    public static function set($profile_id, $type, $value = null, $location = null)
    {
        $kyc = new self();
        $entry = $kyc->where('type', $type)->where('user_profile_id', $profile_id)->first();
        //$location = \geoip(request()->getClientIp())->getAttribute('city').', '.\geoip(request()->getClientIp())->getAttribute('country');

        if($entry) {
            $entry->value = $value;
            $entry->last_location = $location;
            $entry->save();

            if ($entry->type === $type) {
                return true;
            }
        } else {
            $kyc->user_profile_id = $profile_id;
            $kyc->type = $type === 'photo' ? 'profile_photo' : $type;
            $kyc->value = $value;
            $kyc->last_location = $location;
            $kyc->save();

            if ($kyc->type === $type) {
                return true;
            }
        }
        return false;
    }
}
