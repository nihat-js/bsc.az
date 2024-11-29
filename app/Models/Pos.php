<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'pos';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'category_id',
        'is_visible',
        'image',
        'phone1',
        'phone2',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'is_visible' => 'boolean', // Cast 'is_visible' to a boolean
    ];

    // Define relationships (if necessary)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function translations()
    {
        return $this->hasMany(PosTranslation::class, 'pos_id', 'id');
    }
}
