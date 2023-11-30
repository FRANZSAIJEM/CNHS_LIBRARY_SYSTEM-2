<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultFinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing records with the default value
        DB::table('default_fines')->updateOrInsert(['description' => 'your_existing_description'], ['amount' => 1.00]);
        DB::table('borrow_counts')->updateOrInsert(['count' => 5]);

    }
}
