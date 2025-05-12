<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'ورزشی', 'slug' => 'sports'],
            ['name' => 'سیاسی', 'slug' => 'political'],
            ['name' => 'اقتصادی', 'slug' => 'economic'],
            ['name' => 'تکنولوژی', 'slug' => 'technology'],
            ['name' => 'خارجی', 'slug' => 'foreign'],
            ['name' => 'داخلی', 'slug' => 'domestic'],
        ]);
    }
}
