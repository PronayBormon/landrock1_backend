<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'About Us',
            'Contact Us',
            'Privacy Policy',
            'Terms & Conditions',
            'Refund Policy',
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => Str::slug($page)],
                [
                    'title'     => $page,
                    'is_active' => true,
                ]
            );
        }
    }
}
