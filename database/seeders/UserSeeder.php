<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            User::factory()->create([
                'cpf' => env('APP_USER_CPF_DEFAUL', 70123776074),
                'id_profile' => 1,
                'password' => Hash::make(env('APP_USER_PASS_DEFAULT', 'u6NQ#01t')),
                'cpf_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}