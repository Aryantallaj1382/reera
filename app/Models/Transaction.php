<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['wallet_id', 'operation_type', 'amount', 'status', 'cart_number'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
