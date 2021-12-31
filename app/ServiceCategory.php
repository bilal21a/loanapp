<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $table = "service_categories"; 

    protected $fillable = [
        "name",
        "type",
        "service_charge",
        "status",
        "logo"
    ];

    public function subcategories() {
        return $this->hasMany(self::class, "parent", "id");
    }

    public function services() {
        return $this->hasMany(Services::class, "category_id");
    }
}
