<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'surname' => 'Admin',
            'sex' => 'Male',
            'image_path' => '',
            'role' => 'admin',
            'email' => 'admin@gogocarrentals.com',
            'phone' => '000000000',
            'license' => '00000/00/0000',
            'birth' => '1999-12-31',
            'town' => 'Warszawa',
            'zip_code' => '00-000',
            'country' => 'Polska',
            'password' => Hash::make('admin123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jan',
            'surname' => 'Kowalski',
            'sex' => 'Male',
            'image_path' => 'users/qAMzpzD6RghF5qgOstfMz1M3sqLVYIwem9Nb4gAP.png',
            'role' => 'user',
            'email' => 'jankowalski@gmail.com',
            'phone' => '435675345',
            'license' => '54345/12/7853',
            'birth' => '1990-05-24',
            'town' => 'Poznań',
            'zip_code' => '60-001',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Anna',
            'surname' => 'Nowak',
            'sex' => 'Female',
            'image_path' => '',
            'role' => 'user',
            'email' => 'annanowak@gmail.com',
            'phone' => '543678223',
            'license' => '73425/23/6475',
            'birth' => '1996-02-20',
            'town' => 'Wrocław',
            'zip_code' => '50-010',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Zygmunt',
            'surname' => 'Jaskra',
            'sex' => 'Male',
            'image_path' => '',
            'role' => 'user',
            'email' => 'zygmuntjaskra@gmail.com',
            'phone' => '653877464',
            'license' => '53423/53/8523',
            'birth' => '1980-11-06',
            'town' => 'Gdańsk',
            'zip_code' => '80-022',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Grażyna',
            'surname' => 'Gruz',
            'sex' => 'Female',
            'image_path' => '',
            'role' => 'user',
            'email' => 'grazynagruz@gmail.com',
            'phone' => '834023859',
            'license' => '63293/63/2362',
            'birth' => '1990-06-12',
            'town' => 'Kraków',
            'zip_code' => '30-262',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jacek',
            'surname' => 'Mach',
            'sex' => 'Male',
            'image_path' => '',
            'role' => 'user',
            'email' => 'jacekmach@gmail.com',
            'phone' => '754855235',
            'license' => '82352/52/2572',
            'birth' => '1985-04-22',
            'town' => 'Zabrze',
            'zip_code' => '41-723',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jacek',
            'surname' => 'Mach',
            'sex' => 'Male',
            'image_path' => '',
            'role' => 'user',
            'email' => 'jacekmach@gmail.com',
            'phone' => '754855235',
            'license' => '82352/52/2572',
            'birth' => '1985-04-22',
            'town' => 'Zabrze',
            'zip_code' => '41-723',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Krzysztof',
            'surname' => 'Kamis',
            'sex' => 'Male',
            'image_path' => '',
            'role' => 'user',
            'email' => 'krzysztofkamis@gmail.com',
            'phone' => '685675234',
            'license' => '35232/53/1352',
            'birth' => '1982-11-13',
            'town' => 'Lublin',
            'zip_code' => '20-432',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('users')->insert([
            'name' => 'Hiacynta',
            'surname' => 'Mak',
            'sex' => 'Female',
            'image_path' => '',
            'role' => 'user',
            'email' => 'hiacyntamak@gmail.com',
            'phone' => '453224553',
            'license' => '35342/22/3424',
            'birth' => '1991-05-30',
            'town' => 'Lubln',
            'zip_code' => '59-337',
            'country' => 'Polska',
            'password' => Hash::make('haslo123'),
        ]);
    }
}
