<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ad;

class DeleteOldAds extends Command
{
    protected $signature = 'ads:delete-old';
    protected $description = 'حذف آگهی‌هایی که بیش از ۱۵ دقیقه از ساختشان گذشته و is_finish=false هستند.';

    public function handle()
    {
        $count = Ad::where('is_finish', 0)
            ->where('created_at', '<', now()->subMinutes(7))
            ->delete();

        $this->info("{$count} آگهی قدیمی حذف شد.");
    }
}


