<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanCategory extends Model
{
    protected $table = "loans";

    protected $fillable = [
        "name", 
        "type", 
        "interest_rate", 
        "interest_type", 
        "max_amount",
        "max_duration",
        "interest_on_default",
        "interest_amount",
        "status",
    ];

    public function loan() {
        return $this->belongsTo(Loan::class, "id");
    }
}
