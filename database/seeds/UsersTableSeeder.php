<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'admin',
            'email'    => 'admin@admin.com',
            'password' => app('hash')->make('123456')
        ]);

        User::create([
            'name'     => 'user',
            'email'    => 'user@user.com',
            'password' => app('hash')->make('654321')
        ]);

        return factory(User::class, 28)->create();
    }
}
