<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslate extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'product_translates';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'product_id',
        'lang_id',
        'slug',
        'name',
        'description',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'product_id' => 'integer',
        'lang_id' => 'integer',
    ];

    // Disable automatic timestamps if you don't have created_at and updated_at
    public $timestamps = true;

    // Define relationships

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relationship to Language
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
