<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'categories';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // Auto-incrementing is true by default, so this line is not necessary unless explicitly required.
    // public $incrementing = false;

    // The data type of the primary key (int by default)
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'parent_id',
        'is_visible',
        'type',
        'has_url',
        'redirect_url',
    ];

    // The attributes that should be hidden for arrays (e.g., sensitive data)
    protected $hidden = [];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_visible' => 'boolean',
        'type' => 'integer',
        'has_url' => 'boolean',
    ];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;

    // Optional: Define default values for attributes
    protected $attributes = [
        'is_visible' => true,  // Defaults to '1'
        'type' => 0,           // Defaults to '0'
        'has_url' => false,    // Defaults to '0'
    ];
}
