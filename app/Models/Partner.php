<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $table = 'partners';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'name',
        'logo',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function getLogoAttribute($value){
        return $value ? asset("/storage/uploads/partners/" . $value )  : null;
    }

}
