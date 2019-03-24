<?php

use App\Author;
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
        
        Author::truncate();
        $this->call(AuthorsTableSeeder::class);
        // $this->call(UsersTableSeeder::class);

        Author::reguard();
    }
}
