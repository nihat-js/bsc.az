<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 
        'cover_image',
        'price',
        'discounted_price',
        'text'
    ];

    public function translations(){
        return $this->hasMany(ServiceTranslation::class);
    }
}
