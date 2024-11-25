<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'payments';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'amount',
        'product_count',
        'operation_id',
        'from_admin',
        'is_blocked',
        'product_name',
        'unvan',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'amount' => 'float', // Cast 'amount' to float
        'product_count' => 'integer',
        'from_admin' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    // Optional: Add the created_at and updated_at if not automatically handled
    public $timestamps = true;
}
