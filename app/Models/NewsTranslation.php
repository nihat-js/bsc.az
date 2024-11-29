<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'news_translates';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'news_id',
        'lang_id',
        'slug',
        'name',
        'description',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'news_id' => 'integer',
        'lang_id' => 'integer',
    ];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;
}
