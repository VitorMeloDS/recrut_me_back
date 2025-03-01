<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;

class EmployeeController extends Controller {
    public function index(Request $request) {
        $query = Employee::whereHas('invite', function ($q) {
            $q->where('status', 'Finalizado');
        });

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('cpf', 'like', "%$search%");
            });
        }

        return response()->json($query->orderBy('name')->paginate(10));
    }

    public function show($id) {
        $employee = Employee::with('user')->findOrFail($id);
        return response()->json($employee);
    }

    public function updateProfile(Request $request, $id) {
        $request->validate([
            'role' => 'required|in:administrador,gente_cultura,colaborador_comum',
            'password' => 'nullable|string|min:6'
        ]);

        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        // Atualizar perfil
        $user->role = $request->role;

        // Se for Administrador ou Gente e Cultura, exigir senha
        if (in_array($request->role, ['administrador', 'gente_cultura'])) {
            if (!$request->password) {
                return response()->json(['error' => 'A senha é obrigatória para perfis de Administrador e Gente e Cultura.'], 400);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Perfil atualizado com sucesso!']);
    }

    public function exportToExcel(Request $request) {
        return Excel::download(new EmployeesExport($request->search), 'colaboradores.xlsx');
    }
}