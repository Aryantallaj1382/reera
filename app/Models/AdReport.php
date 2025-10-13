<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdReport extends Model
{
    public $table = 'ads_reports';
    protected $fillable = [
        'reporter_id',
        'ad_id',
        'reason',
        'status',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
    public function ad(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ad::class, 'ad_id');
    }
}
