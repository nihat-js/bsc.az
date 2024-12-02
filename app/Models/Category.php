<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id';


    protected $fillable = [
        'parent_id',
        'is_visible',
        'url',
        // 'type',
        // 'has_url',
        // 'redirect_url',
    ];

    protected $hidden = [];

    // The attributes that should be cast to native types
    protected $casts = [
        "parent_id" => 'integer',
        'is_visible' => 'boolean',
        'url' => 'string',
        'type' => 'integer',

    ];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;

    // Optional: Define default values for attributes
    protected $attributes = [
        'is_visible' => true,  // Defaults to '1'
        'type' => 0,           // Defaults to '0'
        'has_url' => false,    // Defaults to '0'
    ];

    public function translations(){
        return $this->hasMany(CategoryTranslation::class, 'category_id', 'id');
    }
}
