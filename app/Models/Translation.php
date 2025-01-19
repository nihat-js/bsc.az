<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $fillable = [
        "table_name",
        "table_id",
        "lang_code",
        "text"
    ];

    protected $hidden = [
        "table_name",
        "table_id",
        "created_at",
        "updated_at"
    ];
}
