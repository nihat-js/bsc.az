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
        "category_id",

        "name",
        "slug",
        "description",
        
        "price",
        "discount_price",
        "is_visible",
        "cover_image",
        "country_id",
        "brand_id",
        "weight",
        "dimension",
        // color
    ];
    protected $casts = [
        'discount_price' => 'double', // Cast 'discount_price' to a double
        'price' => 'double', // Cast 'price' to a double
    ];

    public function getCoverImageAttribute($value){
        return $value ? asset("/storage/uploads/products/" . $value )  : null;
    }

    // Disable automatic timestamps since the table has created_at and updated_at
    public $timestamps = true;

    // Define relationships (if any, such as category or other related models)
    public function category()
    {
        return $this->belongsTo(Category::class); // Assuming you have a Category model
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
    }

    public function colors(){
        return $this->hasMany(ProductColor::class);
    }

    public function images(){
        return $this->hasMany(ProductImage::class);
    }
}
