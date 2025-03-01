<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registers = [
            [ 'name' => 'Colaboradores' ],
            [ 'name' => 'Convite' ],
            [ 'name' => 'Perfil' ],
        ];

        if (Menu::count() == 0) {
            foreach ($registers as $register) {
                Menu::create($register);
            }
        }
    }
}