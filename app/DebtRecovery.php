<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DebtRecovery extends Model
{
    protected $table = "debt_recoveries";
    protected $fillable = [
        "loan_id", "monthly_payment_amount", "start_date", "end_date", "last_amount_to_pay",
        "end_date_balance","last_date_to_pay"
    ];

    public function loan() {
        return $this->belongsTo(Loan::class, 'id');
    }
}
