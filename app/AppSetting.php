<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'app_settings';
    protected $fillable = ['name','value','status','user_profile_id'];

    public function user() {
        return $this->belongsTo(Profile::class, 'user_profile_id', 'id');
    }
}
