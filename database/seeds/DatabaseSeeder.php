<?php

use App\User;
use App\Author;
use App\Book;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::unguard();
        User::unguard();
        Book::unguard();

        Author::truncate();
        User::truncate();
        Book::truncate();

        $this->call(AuthorsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        // $this->call(BooksTableSeeder::class);

        Author::reguard();
        User::reguard();
        Book::reguard();
    }
}
