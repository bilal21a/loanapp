<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $fillable = [
        "ref",
        "user_profile_id",
        "amount",
        "account_id",
        "vendor",
        "description",
        "status",
        "type",
        "sub_type",
        "beneficiary"
    ];

    public function user() {
      return $this->belongsTo(Profile::class, 'id');
    }

    public function transaction($user, $ref, $amount, $account_id, $vendor, $status, $type, $subtype, $desc ) {
      Transaction::create([
        "user_profile_id" => $user->id,
        "ref" => $ref,
        "account_id" => $account_id,
        "amount" => $amount,
        "type" => $type,
        "sub_type" => $subtype,
        "beneficiary" => $user->first_name.' '.$user->last_name,
        "vendor" => $vendor,
        "description" => $desc,
        "status" => $status,
      ]);
    }
}
