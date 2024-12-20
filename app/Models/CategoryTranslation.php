<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'category_translations';

    protected $primaryKey = 'id';

    protected $keyType = 'int';
    protected $fillable = [
        'category_id',
        'lang_code',
        'slug',
        'name',
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        'category_id' => 'integer',
        'lang_code' => 'string',
    ];

    public $timestamps = true;
}
