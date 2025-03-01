<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings {
    protected $search;

    public function __construct($search = null) {
        $this->search = $search;
    }

    public function collection() {
        $query = Employee::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%$this->search%")
                  ->orWhere('email', 'like', "%$this->search%")
                  ->orWhere('cpf', 'like', "%$this->search%");
            });
        }

        return $query->select('name', 'email', 'cpf', 'phone', 'cep', 'uf', 'city', 'neighborhood', 'street')->get();
    }

    public function headings(): array {
        return ['Nome', 'E-mail', 'CPF', 'Telefone', 'CEP', 'UF', 'Cidade', 'Bairro', 'Logradouro'];
    }
}