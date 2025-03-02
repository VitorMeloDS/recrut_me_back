<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Employee::count() == 0) {
            Employee::create([
                'id_user' => 1,
                'name' => 'Matheus Feitosa da silva',
                'cpf' => env('APP_USER_CPF_DEFAUL', '70123776074'),
                'email' => 'matheus.silva@gmail.com',
                'cep' => '29138376',
                'phone' => '82988691357',
                'uf' => 'ES',
                'locality' => 'Viana',
                'neighborhood' => 'Campo Verde',
                'street' => 'Rua A'
            ]);
        }
    }
}