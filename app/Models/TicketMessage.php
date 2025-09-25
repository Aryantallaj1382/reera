<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $fillable = [
        'ticket_id', 'message', 'is_support'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

