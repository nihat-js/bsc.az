<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySpecs extends Model
{
    protected $fillable = [
        'category_id',
        'group_id',
        'name',
        'show_in_filter',
    ];

    public function casts(){
        return [
            'show_in_filter' => 'boolean'
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function translations()
    {
        return $this->hasMany(Translation::class,"table_id")
            ->where("table_name", "category_specs")
            ->where("table_id", $this->id);
    }

}
