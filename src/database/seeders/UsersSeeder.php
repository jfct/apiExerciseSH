<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //DB::table('users')->delete();
        
        DB::table('users')->insert([
            'id'            => 1,
            'name'          => 'test_manager',
            'password'      =>  app('hash')->make('teste1'),
            'usersTypeId'    => '1'
        ]);

        DB::table('users')->insert([
            'id'            => 2,
            'name'          => 'test_technician',
            'password'      =>  app('hash')->make('teste2'),
            'usersTypeId'    => '2'
        ]);
    }
}
