<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'is_visible',
        'image',
    ];
    protected $casts = [
        'is_visible' => 'boolean',  // Cast 'is_visible' to a boolean
    ];

    // protected $dates = ['deleted_at'];

    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(NewsTranslation::class, 'news_id', 'id');
    }
}
