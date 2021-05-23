<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class TestTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('tasks')->delete();
        
        DB::table('tasks')->insert([
            'id'        => 1,
            'userId'    => 2,
            'summary'   => 'test',
            'date'      =>  date('Y-m-d H:i:s'),
        ]);
        DB::table('tasks')->insert([
            'id'        => 2,
            'userId'    => 2,
            'summary'   => 'test',
            'date'      =>  date('Y-m-d H:i:s'),
        ]);
        DB::table('tasks')->insert([
            'id'        => 3,
            'userId'    => 2,
            'summary'   => 'test',
            'date'      =>  date('Y-m-d H:i:s'),
        ]);
    }
}
