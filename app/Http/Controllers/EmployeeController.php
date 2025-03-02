<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExports;


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
        $employee = Employee::findOrFail($id)->first();
        $user = User::where('id', '=', $employee->id_user)->first();

        $authUser = User::where('id', $request->user()->id)
        ->with('employee')
        ->with('profile')
        ->with('profile.menus')
        ->first();

        // Verifique se o usuário que está fazendo a solicitação tem permissão para alterar o perfil
        if ($authUser->id == 2 && $authUser->id == 1) {
            return response()->json(['error' => 'Você não tem permissão para promover alguém para Administrador.'], 403);
        }

        // Definir regras de alteração de perfil
        $allowedRoles = [];
        // Administrador pode alterar para qualquer perfil
        if ($authUser->id_profile == 1) {
            $allowedRoles = [1, 2, 3];
        // Gente e Cultura pode alterar para Gente e Cultura ou Colaborador Comum
        } elseif ($authUser->id_profile == 2) {
            $allowedRoles = [2, 3];
        // Colaborador Comum não pode alterar seu próprio perfil
        } elseif ($authUser->id_profile == 3) {
            return response()->json(['error' => 'Colaboradores comuns não podem alterar seu próprio perfil.'], 403);
        }

        $request->validate([
            'role' => ['required', function ($attribute, $value, $fail) use ($allowedRoles) {
                if (!in_array($value, $allowedRoles)) {
                    $fail('Você não tem permissão para atribuir esse perfil.');
                }
            }],
            'password' => [
                'nullable',
                'string',
                'min:6',
                function ($attribute, $value, $fail) use ($request) {
                    // A senha é obrigatória para perfis de Administrador e Gente e Cultura
                    if (in_array($request->role, [1, 2]) && empty($value)) {
                        $fail('A senha é obrigatória para perfis de Administrador e Gente e Cultura.');
                    }
                }
            ],
        ]);

        // Atualiza os dados do usuário
        $user->id_profile = $request->role;
        $user->disabled = false;

        // Atualiza a senha se necessário
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Salva as alterações
        $user->save();

        return response()->json(['message' => 'Perfil atualizado com sucesso!']);
    }

    public function exportToExcel(Request $request) {
        return Excel::download(new EmployeesExports($request->search), 'colaboradores.xlsx');
    }
}