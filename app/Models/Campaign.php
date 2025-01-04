<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        "name",
        "slug",
        "description",
        "text",
        "cover_image",
        "start_date",
        "end_date"
    ];

    public function translations()
    {
        return $this->hasMany(CampaignTranslation::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
