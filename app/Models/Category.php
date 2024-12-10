<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'id';


    protected $fillable = [
        'parent_id',
        "name",
        "slug",
        'is_visible',
    ];

    protected $hidden = [];

    protected $casts = [
        "parent_id" => 'integer',
        'is_visible' => 'boolean',

    ];

    // public $timestamps = true;
    // protected $attributes = [
    //     'is_visible' => true,  // Defaults to '1'
    // ];

    public function translations(){
        return $this->hasMany(CategoryTranslation::class, 'category_id', 'id');
    }
}
