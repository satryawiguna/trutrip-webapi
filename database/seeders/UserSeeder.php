<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = (new Role())->where('name', '=', 'Admin')->first();

        $user = User::create([
            'role_id' => $role->id,
            'username' => 'admin',
            'email' => 'admin@loopit.co',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // 12345678
            'remember_token' => Str::random(10),
        ]);

        $user->contact()->create([
            'nick_name' => 'Admin',
            'full_name' => 'Administrator'
        ]);

//        User::factory()->count(9)->for(
//                Contact::factory(), 'contact'
//            )->create();

        Contact::factory()->count(9)->create();
    }
}
