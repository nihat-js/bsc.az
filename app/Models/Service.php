<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description', 
        'text',
        'price',
        'discounted_price',
        'cover_image',
        'is_visible'
    ];

    public function getCoverImageAttribute($value){
        return $value ? asset("/storage/uploads/services/" . $value )  : null;
    }

    public function translations(){
        return $this->hasMany(ServiceTranslation::class);
    }
}
