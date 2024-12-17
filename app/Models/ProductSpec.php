<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpec extends Model
{
    protected $fillable = [
        'product_id',
        "spec_id",
        'text',
        "option_id",
    ];


    public function translations()
    {
        return $this->hasMany(Translation::class, "table_id", "id")
            ->where("table_name", "product_specs");
    }
}
