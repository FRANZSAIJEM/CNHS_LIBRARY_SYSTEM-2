<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 500) as $index) {
            DB::table('books')->insert([
                'title' => $faker->sentence,
                'author' => $faker->name,
                'subject' => $faker->word,
                'availability' => $faker->randomElement(['Available', 'Not Available']),
                'status' => $faker->randomElement(['Good', 'Damage']),
                'publish' => $faker->sentence,
                'isbn' => $faker->isbn13,
                'description' => $faker->paragraph,
                'image' => 'storage/Other.png', // You can set a default image path here.
            ]);
        }
    }
}
