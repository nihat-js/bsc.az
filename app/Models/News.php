<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        "name",
        "slug",
        "description",
        'cover_image',
        'is_visible',
    ];
    protected $casts = [
        'is_visible' => 'boolean',  
    ];


    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class, 'news_id', 'id');
    }
}
