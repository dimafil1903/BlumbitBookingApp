<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // modify to following commands fit your table structure
        $role = Role::create(['name' => 'admin']);
        $user = User::create(['email' => 'admin@gmail.com', 'password' => bcrypt('secret')]);
        DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => $role->id]);

    }
}
