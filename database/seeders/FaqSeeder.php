<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Faq::insert([
            [
                'question' => 'How do I create an account?',
                'answer' => 'Click on the register button and fill in your details.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How can I reset my password?',
                'answer' => 'Use the “Forgot Password” option on the login page.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ]);
    }
}
