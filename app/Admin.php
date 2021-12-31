<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminPasswordResetNotification;
use App\Notifications\AdminResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    protected $guard = 'admin';

    protected $table = "admin_users";
    protected $fillable = ["name", "gender", "phone_number", "email", "password", "is_active"];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token) {
        $this->notify(new AdminPasswordResetNotification($token));
    }

}
