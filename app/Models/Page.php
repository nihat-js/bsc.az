<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'pages';

    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        "name",
        "slug",
        "title",
        "text",
        'is_visible',
        'image',
    ];

    protected $casts = [
        'type' => 'integer',
        'is_main' => 'boolean',
        'is_visible' => 'boolean',
    ];
    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(PageTranslation ::class);
    }
}
