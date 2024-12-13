<?php

namespace App\Models;

use App\Models\Admin\CategorySpecOption;
use Illuminate\Database\Eloquent\Model;

class CategorySpecs extends Model
{
    protected $fillable = [
        'category_id',
        'group_id',
        'name',
        'show_in_filter',
        'filter_type',
        "auto_save_options" 
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

    public function options()
    {
        return $this->hasMany(CategorySpecOption::class, 'category_spec_id');
    }

}
