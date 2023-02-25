<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::create([
             'firstname' => "Basiru",
            'lastname' =>  "Yekinni",
            'email' => "kolade2127@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('Appspace@24'), // Appspace@24
            'role_id' => Role::ADMIN,
            'remember_token' => Str::random(10),
        ]);
        User::factory(6)->hasDetail(1)->create();
        User::factory(3)->hasDetail(1)->unverified()->create();
    }
}
