<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'category',
        'amount',
        'transaction_date',
        'note',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];
}
