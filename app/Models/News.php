<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    // The table associated with the model.
    protected $table = 'news';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'is_visible',
        'image',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_visible' => 'boolean',  // Cast 'is_visible' to a boolean
    ];

    // Optional: Enable SoftDeletes to manage the 'deleted_at' field
    protected $dates = ['deleted_at'];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;
}
