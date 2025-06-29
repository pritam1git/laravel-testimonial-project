<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Crud;

class CrudSeeder extends Seeder
{
    public function run(): void
    {
        Crud::truncate(); // Optional: clean table

        $data = [
            [
                'heading' => 'Great Service!',
                'text' => 'I loved the experience. Highly recommended!',
                'image' => 'demo1.jpg',
            ],
            [
                'heading' => 'Awesome Support',
                'text' => 'Support team was very responsive and helpful.',
                'image' => 'demo2.jpg',
            ],
            [
                'heading' => 'Smooth Interface',
                'text' => 'User interface is simple and easy to use.',
                'image' => 'demo3.jpg',
            ],
        ];

        foreach ($data as $item) {
            Crud::create($item);
        }
    }
}
