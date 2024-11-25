<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'settings';

    // The primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'key',
        'value',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'key' => 'string', // Cast 'key' as string
        'value' => 'string', // Cast 'value' as string (or you can use 'array' if storing serialized data)
    ];

    // Disable timestamps since the table does not have created_at and updated_at
    public $timestamps = false;
}
