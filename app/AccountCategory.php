<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountCategory extends Model
{
    protected $table = "account_category";

    protected $fillable = [
        "name",
        "type",
        "parent",
        "status",
        "interest_rate",
        "interest_interval",
    ];

    public function account() {
        return $this->hasMany(Account::class, "account_category_id");
    }

    public function subCategories() {
        return $this->hasMany(self::class, "parent", "id");
    }

    public function defaultAccounts() {
        return self::select("name", "id", "type")->where("type", "Savings")->with("subCategories")->get();
    }
}
