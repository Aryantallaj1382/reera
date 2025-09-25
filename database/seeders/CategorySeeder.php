<?php

namespace Database\Seeders;

// database/seeders/CategorySeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            1 => ['title' => 'املاک', 'icon' => 'icons/home.svg'],
            2 => ['title' => 'وسایل نقلیه', 'icon' => 'icons/car.svg'],
            3 => ['title' => 'کالای دیجیتال', 'icon' => 'icons/digital.svg'],
            4 => ['title' => 'خانه و آشپزخانه', 'icon' => 'icons/kitchen.svg'],
            5 => ['title' => 'خدمات', 'icon' => 'icons/service.svg'],
            6 => ['title' => 'وسایل شخصی', 'icon' => 'icons/personal.svg'],
            7 => ['title' => 'سرگرمی و فراغت', 'icon' => 'icons/fun.svg'],
            8 => ['title' => 'اجتماعی', 'icon' => 'icons/social.svg'],
            9 => ['title' => 'تجهیزات و صنعتی', 'icon' => 'icons/industry.svg'],
            10 => ['title' => 'کاریابی و استخدام', 'icon' => 'icons/job.svg'],
        ];

        foreach ($categories as $id => $data) {
            DB::table('categories')->updateOrInsert([
                'id' => $id,
            ], [
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'icon' => $data['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
