<?php

use App\User;
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
        User::unguard();
        
        Author::truncate();
        User::truncate();
        $this->call(AuthorsTableSeeder::class);
        $this->call(UsersTableSeeder::class);

        Author::reguard();
        User::reguard();
    }
}
