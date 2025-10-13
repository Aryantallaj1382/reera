<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAd extends Model
{
    use HasFactory;

    protected $table = 'ticket_ads';

    protected $fillable = [
        'ad_id',
        'ticket_type_id',
        'number',
        'date',
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
        return $this->belongsTo(Ad::class, 'ad_id');
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }

    /**
     * ارتباط با ارز
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currencies_id');
    }    public function getMobileAttribute()
{
    if ($this->my_phone == true) {
        return $this->ad->user->mobile;
    } elseif ($this->other_phone == true) {
        return $this->other_phone_number;

    }
    return null;

}
}
