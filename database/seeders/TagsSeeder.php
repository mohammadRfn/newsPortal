<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insert([
            ['name' => 'ورزشی'],
            ['name' => 'سیاسی'],
            ['name' => 'اقتصادی'],
            ['name' => 'تکنولوژی'],
            ['name' => 'خارجی'],
            ['name' => 'داخلی'],
            ['name' => 'سلامتی'],
            ['name' => 'مسافرت'],
            ['name' => 'آموزش'],
        ]);
    }
}
