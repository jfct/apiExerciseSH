<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsersTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('usersType')->delete();
        
        DB::table('usersType')->insert([
            'id'            => 1,
            'type'          => 'Manager',
            'created_at'    => date('Y-m-d H:i:s')
        ]);
        DB::table('usersType')->insert([
            'id'            => 2,
            'type'          => 'Technician',
            'created_at'    => date('Y-m-d H:i:s')
        ]);
    }
}
