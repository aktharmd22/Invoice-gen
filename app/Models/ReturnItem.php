<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $table = 'return_items';

    protected $fillable = ['return_id', 'bill_item_id', 'refund_amount'];

    public function returnRecord()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id');
    }

    public function billItem()
    {
        return $this->belongsTo(BillItem::class, 'bill_item_id');
    }
}
