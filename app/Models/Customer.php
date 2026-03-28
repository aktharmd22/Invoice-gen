<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone'];

    public function bills()
    {
        return $this->hasMany(Bill::class, 'phone', 'phone');
    }
}
