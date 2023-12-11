<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File; // Corrected namespace

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $famousTitles = [
            'To Kill a Mockingbird',
            '1984',
            'The Great Gatsby',
            'One Hundred Years of Solitude',
            'Brave New World',
            'The Catcher in the Rye',
            'The Lord of the Rings',
            'Pride and Prejudice',
            'The Hobbit',
            'The Chronicles of Narnia',
            'The Da Vinci Code',
            'The Harry Potter series',
            'The Bible',
            'The Qur\'an',
            // Add more famous book titles as needed
        ];

        $imageDirectory = 'public/storage/book_images';
        $existingImages = File::allFiles($imageDirectory);

        $subjects = [
            'Periodical subscription',
            'English',
            'Science',
            'Mathematics',
            'Senior High',
            'Additional Supplementary Readers',
            'Encyclopedia',
        ];


        $famousAuthors = [
            'J.K. Rowling',
            'Stephen King',
            'George R.R. Martin',
            'J.R.R. Tolkien',
            'Agatha Christie',
            'Jane Austen',
            'Harper Lee',
            'F. Scott Fitzgerald',
            'Ernest Hemingway',
            'Leo Tolstoy',
            'Charles Dickens',
            'Mark Twain',
            'Homer',
            'William Shakespeare',
            'Gabriel Garcia Marquez',
            'Victor Hugo',
            'Herman Melville',
            'Emily Bronte',
            'Charlotte Bronte',
            'Arthur Conan Doyle',
            'H.G. Wells',
            'Jules Verne',
            'Lewis Carroll',
            'Aldous Huxley',
            'George Orwell',
            'Virginia Woolf',
            'Fyodor Dostoevsky',
            'Toni Morrison',
            'Albert Camus',
            'Gabriel Garcia Marquez',
            'Kurt Vonnegut',
            'Ray Bradbury',
            'Jane Goodall',
            'Malcolm Gladwell',
            'Noam Chomsky',
            // Add more famous author names as needed
        ];


        foreach (range(1, 500) as $index) {
            $publishYear = $faker->numberBetween(1765, 2023);

            $selectedImage = $faker->randomElement($existingImages);
            $imagePath = 'storage/book_images/' . $selectedImage->getFilename();


            DB::table('books')->insert([
                'title' => $faker->randomElement($famousTitles),
                'author' => $faker->randomElement($famousAuthors),
                'subject' => $faker->randomElement($subjects),
                'availability' => $faker->randomElement(['Available', 'Not Available']),
                'status' => $faker->randomElement(['Good', 'Damage']),
                'condition' => $faker->randomElement(['New Acquired', 'Outdated']),
                'publish' => $publishYear,
                'isbn' => $faker->isbn13,
                'description' => $faker->paragraph,
                'image' => $imagePath,
            ]);
        }
    }
}
