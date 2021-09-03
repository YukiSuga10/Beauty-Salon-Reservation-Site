<?php

use Illuminate\Database\Seeder;


class ReserveTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reserves')->insert([
            [
                "admin_id"       => 1,
                "user_id"        => 1,
                "stylist_id"     => 2,
                "menu"           => "カット",
                "date"           => "2021-08-20",
                "startTime"      => "10:00:00",
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                "admin_id"       => 1,
                "user_id"        => 1,
                "stylist_id"     => 2,
                "menu"           => "カット",
                "date"           => "2021-08-18",
                "startTime"      => "13:00:00",
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                "admin_id"       => 1,
                "user_id"        => 1,
                "stylist_id"     => 3,
                "menu"           => "カラー",
                "date"           => "2021-08-15",
                "startTime"      => "13:00:00",
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                "admin_id"       => 1,
                "user_id"        => 1,
                "stylist_id"     => 1,
                "menu"           => "カット",
                "date"           => "2021-08-11",
                "startTime"      => "09:00:00",
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
