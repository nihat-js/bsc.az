<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $table = 'product_translations';

    protected $primaryKey = 'id';

    protected $keyType = 'int';
    protected $fillable = [
        'product_id',
        'lang_code',
        'slug',
        'name',
        'description',
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
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
