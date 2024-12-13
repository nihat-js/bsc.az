<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorCatalog extends Model
{
    public $table = 'color_catalog';
    protected $fillable = [
        'name',
        'hex',
    ];

    public function translations(){
        return $this->hasMany(Translation::class, 'table_id', 'id')
            ->where('table_name', 'color_catalog');
    }
}
