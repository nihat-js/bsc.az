<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslate extends Model
{
    use HasFactory;

    protected $table = 'product_translates';

    protected $primaryKey = 'id';

    protected $keyType = 'int';
    protected $fillable = [
        'product_id',
        'lang_id',
        'slug',
        'name',
        'description',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'lang_id' => 'integer',
    ];

    public $timestamps = true;

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
