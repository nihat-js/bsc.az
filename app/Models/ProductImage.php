<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'product_images';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'product_id',
        "path",
        "rank"
    ];
    

    // The attributes that should be cast to native types
    protected $casts = [
        "path" => "string",
        
        // 'is_main' => 'boolean', // Cast 'is_main' to boolean
        // 'is_visible' => 'boolean', // Cast 'is_visible' to boolean
    ];

    // Define relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
