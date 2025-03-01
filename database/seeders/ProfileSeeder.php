<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registers = [
            [ 'name' => 'Administrador' ],
            [ 'name' => 'Gente e cultura' ],
            [ 'name' => 'Colaborador' ]
        ];

        if (Profile::count() == 0) {
            foreach ($registers as $register) {
                Profile::create($register);
            }
        }
    }
}