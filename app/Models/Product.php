<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $fillable = [
        'is_visible',
        'add_basket',
        'discount_price',
        'price',
        'file',
    ];

    protected $casts = [
        'is_visible' => 'boolean', // Cast 'is_visible' to a boolean
        'add_basket' => 'boolean', // Cast 'add_basket' to a boolean
        'discount_price' => 'double', // Cast 'discount_price' to a double
        'price' => 'double', // Cast 'price' to a double
    ];

    // Disable automatic timestamps since the table has created_at and updated_at
    public $timestamps = true;

    // Define relationships (if any, such as category or other related models)
    public function category()
    {
        return $this->belongsTo(Category::class); // Assuming you have a Category model
    }

    public function translations(){
        return $this->hasMany(ProductTranslate::class);
    }
}
