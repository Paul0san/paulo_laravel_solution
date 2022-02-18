<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Default User
        $newUser = ModelsUser::create([
            'name' => 'Paulo',
            'last_name' => 'Sanchez',
            'gender' => 'male',
            'age' => '22',
            'email' => 'paulo@prueba.com',
            'password' => Hash::make('admin1234'),
        ]);

        $newUser->user_personal_info()->create([
            'user_id' => $newUser->id,
            'city' => 'Córdoba',
            'state' => 'Veracruz',
            'country' => 'México',
            'favorite_marvel_character' => 'Hulk',
            'favorite_marvel_comic' => 'HULK VS. THOR: BANNER OF WAR ALPHA 1 (2022) #1',
        ]);

        $newUser->save();
    }
}
