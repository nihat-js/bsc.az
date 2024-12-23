<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{   
    protected $table = 'basket';
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    protected $hidden = ['created_at', 'updated_at','user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
