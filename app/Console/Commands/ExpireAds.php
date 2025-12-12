<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;
use Carbon\Carbon;

class ExpireAds extends Command
{
    protected $signature = 'ads:expire';
    protected $description = 'Expire ads whose expiration_date has passed';

    public function handle()
    {
        $now = Carbon::now();

        $expiredAds = Ad::where('expiration_date', '<', $now)
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        $this->info("$expiredAds ads expired successfully.");
    }
}
