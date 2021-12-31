<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Profile extends Authenticatable implements JWTSubject
{

    use Notifiable;

    protected $table = "user_profile";

    protected $fillable = [
        "first_name",
        "last_name",
        "gender",
        "city",
        "email",
        "phone_number",
        "password",
        "email_verified_at",
        "is_active",
        "isVerified",
        "user_type"
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function banks() {
        return $this->hasMany(Bank::class, "user_profile_id");
    }

    public function cards() {
        return $this->hasMany(Card::class, "user_profile_id");
    }

    public function accounts() {
        return $this->hasMany(Account::class, "user_profile_id");
    }

    public function investments() {
        return $this->hasMany(UserInvestment::class, "user_profile_id");
    }

    public function loans() {
        return $this->hasMany(Loan::class, "user_profile_id");
    }

    public function kyc() {
        return $this->hasOne(Kyc::class, "user_profile_id");
    }

    public function kycs() {
        return $this->hasMany(UserKyc::class, "user_profile_id");
    }

    public function autoSavingConfig() {
        return $this->hasMany(AutoSaving::class, "user_profile_id");
    }

    public function app_settings() {
        return $this->hasMany(AppSetting::class, "user_profile_id");
    }

    public function withdrawalSettings() {
        return $this->belongsTo(Withdrawal::class, "user_profile_id");
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "user_profile_id");
    }

    public function total_users() {
        return self::where("is_active", 1)->count();
    }

    public function tickets() {
        return $this->hasMany(Ticket::class, 'id');
    }


    public function referees() {
        return $this->hasMany(Referer::class, 'referer_id' );
    }

    public function affiliates() {
        return $this->hasMany(PartnerReferer::class, 'partner_id');
    }

    public function referer() {
        return $this->hasOne(Referer::class, 'user_profile_id');
    }

    public function nextOfKin() {
        return $this->hasOne(NextOfKin::class, 'user_profile_id');
    }
    public function employment() {
        return $this->hasOne(EmploymentData::class, 'user_profile_id');
    }

    public function social_links() {
        return $this->hasMany(SocialLink::class, 'user_profile_id');
    }

    public function agentApproval() {
        return $this->hasOne(AgentApproval::class, 'user_profile_id');
    }

    public function approvals() {
        return $this->hasMany(AgentApproval::class, 'agent_id');
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function  kycApprovalStatus() {
        $kycStatus = false;
        $socialStatus = false;

       foreach($this->kycs as $kyc ) {
           if($kyc->status == 0) {
               $kycStatus = true;
           }
       }

       foreach ($this->social_links() as $social) {
           if($social->status == 0) {
               $socialStatus = true;
           }
       }

       if(count($this->kycs) < 1 || !$this->agentApproval->status || !$this->employment->status || $socialStatus || $kycStatus || !$this->nextOfKin->status) {
           return false;
       }

       return true;

    }

}
