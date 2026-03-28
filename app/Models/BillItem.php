<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    protected $fillable = [
        'bill_id', 'item_name', 'original_price',
        'discount_amount', 'final_price', 'quantity', 'is_returned',
    ];

    protected $casts = ['is_returned' => 'boolean'];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class, 'bill_item_id');
    }
}
