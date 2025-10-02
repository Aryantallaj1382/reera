<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessAd extends Model
{

    use HasFactory;

    protected $table = 'business_ads';

    protected $fillable = [
        'ad_id',
        'condition',
        'text',
        'site_massage',
        'my_phone',
        'other_phone',
        'other_phone_number',
        'currencies_id',
        'price',
        'donation',
        'cash',
        'installments',
        'check',
    ];


    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }
}
