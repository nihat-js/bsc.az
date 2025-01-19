<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySpecGroupName extends Model
{
    public $fillable = ['name', 'category_id',"image"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categorySpecs()
    {
        return $this->hasMany(CategorySpecs::class);
    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'table_id', 'id')
        ->where('table_name', 'category_spec_group_names');
    }

    public function getImageAttribute($value)
    {
        return  $value ?  asset('/storage/uploads/category_spec_group_names/' . $value) : null;
    }
}
