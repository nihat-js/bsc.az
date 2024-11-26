<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'key' => 'string', 
        'value' => 'string', 
    ];

    public $timestamps = false;
}
