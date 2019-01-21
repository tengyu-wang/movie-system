<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'name'              => 'Tengyu',
            'email'             => 'tengyu.wang@gmail.com',
            'password'          => Hash::make('111111'),
            'remember_token'    => str_random(32)
        ]);
    }
}
