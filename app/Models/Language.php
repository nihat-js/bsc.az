<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'is_visible',
        'code',
        'name',
        'image'
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_visible' => 'boolean',  // Cast 'is_visible' to a boolean
    ];

    public function getImageAttribute($value)
    {
        return $value ? asset("/storage/uploads/languages/" . $value) : null;
    }

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;
}
