<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'bill_no', 'customer_name', 'phone',
        'subtotal', 'total_discount', 'grand_total',
        'status', 'payment_method', 'payment_status', 'date',
    ];

    protected $casts = ['date' => 'date'];

    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnModel::class);
    }

    public function activeItems()
    {
        return $this->hasMany(BillItem::class)->where('is_returned', false);
    }
}
