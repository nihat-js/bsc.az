<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $table = 'dictionary';
    protected $fillable = ['word', 'meaning'];

    public function translations(){
        return $this->hasMany(Translation::class,"table_id","id")
        ->where("table_name","dictionary");
    }
}
