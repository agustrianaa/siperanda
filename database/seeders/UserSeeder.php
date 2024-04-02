<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Direksi;
use App\Models\SuperAdmin;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'rita@gmail.com',
            'password' => bcrypt('rita12345'),
            'role' =>'super_admin'
        ]);
        if ($user) {
            SuperAdmin::create([
                'name' => 'Rita Manik',
                'user_id' => $user->id,
            ]);
        }

        $user = User::create([
            'email' => 'a@gmail.com',
            'password' => bcrypt('12345678'),
            'role' =>'admin'
        ]);
        if ($user) {
            Admin::create([
                'name' => 'Ini Admin',
                'user_id' => $user->id,
            ]);
        }

        $user = User::create([
            'email' => 'b@gmail.com',
            'password' => bcrypt('12345678'),
            'role' =>'direksi'
        ]);
        if ($user) {
            Direksi::create([
                'name' => 'Ini Direksi',
                'user_id' => $user->id,
            ]);
        }

        $user = User::create([
            'email' => 'ca@gmail.com',
            'password' => bcrypt('12345678'),
            'role' =>'unit'
        ]);
        if ($user) {
            Unit::create([
                'nama_unit' => 'Teknik Informatika',
                'user_id' => $user->id,
            ]);
        }

    }
}
