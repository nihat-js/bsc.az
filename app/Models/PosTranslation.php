<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosTranslation extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'pos_translates';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // Disable automatic timestamps since the table doesn't have created_at and updated_at columns
    public $timestamps = true;

    // The attributes that are mass assignable
    protected $fillable = [
        'pos_id',
        'lang_id',
        'slug',
        'name',
        'address',
    ];

    // The relationships (if needed)

    public function pos()
    {
        return $this->belongsTo(Pos::class, 'pos_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
