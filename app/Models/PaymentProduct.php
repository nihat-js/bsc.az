<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProduct extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'payment_products';

    // Primary key field (optional, since Laravel assumes 'id' by default)
    protected $primaryKey = 'id';

    // The data type of the primary key
    protected $keyType = 'int';

    // Disable automatic timestamps since the table doesn't have created_at and updated_at
    public $timestamps = false;

    // The attributes that are mass assignable
    protected $fillable = [
        'payment_id',
        'product_id',
    ];

    // The relationships (if needed)

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
