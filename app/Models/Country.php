<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $fillable = ['name', 'code', 'phone_code','is_visible'];

    protected $casts = [
        'is_visible' => 'boolean',  // Cast 'is_visible' to a boolean
    ];


    public function translations(){
        return $this->hasMany(Translation::class, 'table_id', 'id')
            ->where('table_name', 'countries');
            // ->select('table_id', 'column_name', 'language_code', 'translation');
    }
}
