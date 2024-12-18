<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsTranslation extends Model
{
    use HasFactory;
    protected $table = 'news_translations';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'news_id',
        'lang_code',
        'slug',
        'name',
        'description',
    ];
    protected $casts = [
        'news_id' => 'integer',
    ];
    public $timestamps = true;
}
