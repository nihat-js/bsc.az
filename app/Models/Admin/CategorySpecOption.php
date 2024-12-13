<?php

namespace App\Models\Admin;

use App\Models\CategorySpecs;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;

class CategorySpecOption extends Model
{
    protected $fillable = ['category_spec_id', 'text'];


    public function translations()
    {
        return $this->hasMany(Translation::class, 'table_id', 'id')
            ->where('table_name', 'category_spec_options');
    }

    public function categorySpec()
    {
        return $this->belongsTo(CategorySpecs::class, 'category_spec_id', 'id');
    }
}
