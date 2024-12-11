<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $fillable = ['name', 'code', 'phone_code'];


    public function translations(){
        return $this->hasMany(Translation::class, 'table_id', 'id')
            ->where('table_name', 'countries');
            // ->select('table_id', 'column_name', 'language_code', 'translation');
    }
}
