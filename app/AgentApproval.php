<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentApproval extends Model
{
    protected $table = 'agent_approvals';
    protected $fillable = ['user_profile_id', 'agent_id', 'status', 'points'];

    public function profile() {
        return $this->belongsTo(Profile::class, 'user_profile_id');
    }

    public function agent() {
        return $this->belongsTo(Profile::class, 'id','agent_id');
    }
}
