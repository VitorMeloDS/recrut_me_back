<?php

namespace Database\Seeders;

use App\Models\MenuProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registers = [
            [
                'id_menu' => 1,
                'id_profile' => 1,
            ],
            [
                'id_menu' => 2,
                'id_profile' => 1,
            ],
            [
                'id_menu' => 3,
                'id_profile' => 1,
            ],
            [
                'id_menu' => 1,
                'id_profile' => 2,
            ],
            [
                'id_menu' => 2,
                'id_profile' => 2,
            ],
        ];

        if (MenuProfile::count() == 0) {
            foreach ($registers as $register) {
                MenuProfile::create($register);
            }
        }
    }
}