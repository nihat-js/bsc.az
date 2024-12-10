<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $fillable = [
        "table_name",
        "lang_code",
        "text"
    ];
}
