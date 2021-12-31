<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'category_id',
        'code',
        'name',
        'discount',
        'status',
        'logo',
        'amount'
    ];

    public function category() {
        return $this->belongsTo(ServiceCategory::class);
    }
}
