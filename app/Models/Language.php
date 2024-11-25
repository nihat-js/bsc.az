<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'languages';

    // Primary key field (optional, Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'is_visible',
        'code',
        'name',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_visible' => 'boolean',  // Cast 'is_visible' to a boolean
    ];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;
}
