<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    use HasFactory;

    protected $table = 'page_translations';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'page_id',
        'lang_code',
        'slug',
        'name',
        'text',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        'page_id' => 'integer',
        'lang_id' => 'integer',
    ];

    public $timestamps = true;
}
