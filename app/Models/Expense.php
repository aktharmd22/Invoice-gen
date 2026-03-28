<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['category', 'amount', 'note', 'expense_date'];

    protected $casts = ['expense_date' => 'date'];
}
