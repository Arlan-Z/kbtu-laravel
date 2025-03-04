<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            Book::factory()->count(rand(2, 5))->create([
                'author_id' => $author->id,
            ]);
        }
    }
}
