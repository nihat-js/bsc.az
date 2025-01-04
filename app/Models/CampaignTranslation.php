<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignTranslation extends Model
{
    protected $fillable = [
        "lang_code",
        "name",
        "slug",
        "description",
        "text"
    ];
}
