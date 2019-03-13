<?php

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
        DB::table('users')->insert([
            'name' => 'Arbnor Osmani',
            'email' => 'arbnori.osmani@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
    }
}
