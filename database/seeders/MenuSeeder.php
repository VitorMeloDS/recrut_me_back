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
            [ 'name' => 'Colaboradores', 'link' => '/painel', 'icon' => 'people' ],
            [ 'name' => 'Convite', 'link' => '/convite', 'icon' => 'person_add' ],
            [ 'name' => 'Perfil', 'link' => '/perfil', 'icon' => 'person' ],
        ];

        if (Menu::count() == 0) {
            foreach ($registers as $register) {
                Menu::create($register);
            }
        }
    }
}