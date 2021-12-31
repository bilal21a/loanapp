<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentData extends Model
{
    protected  $table = 'employment_data';
    protected $fillable = [
        'user_profile_id', 'employment_status',
        'employment_type', 'employer', 'status',
        'approval_status', 'proof_of_employment',
        'last_location', 'salary', 'points'
    ];

    public function user() {
        return $this->belongsTo(Profile::class, 'id');
    }
}
