<?php

use Illuminate\Database\Seeder;
use DateTime;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'name'           => '美容院A',
                'email'          => 'salonA@gmail.com',
                'password'       => Hash::make('salonA1234'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => '美容院B',
                'email'          => 'salonB@gmail.com',
                'password'       => Hash::make('salonB1234'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => '美容院C',
                'email'          => 'salonC@gmail.com',
                'password'       => Hash::make('salonC1234'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'name'           => '美容院D',
                'email'          => 'salonD@gmail.com',
                'password'       => Hash::make('salonD1234'),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
