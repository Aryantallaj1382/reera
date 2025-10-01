<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketType extends Model
{
    use HasFactory;

    protected $table = 'ticket_type';

    protected $fillable = [
        'name',
    ];

    /**
     * هر نوع بلیط می‌تواند چندین آگهی داشته باشد
     */
    public function ticketAds()
    {
        return $this->hasMany(TicketAd::class, 'ticket_type_id');
    }
}
