<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller {
    public function register(Request $request) {
        $request->validate([
            'token' => 'required|exists:invites,token',
            'name' => 'required|string|max:100',
            'cpf' => ['required', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', 'unique:employees,cpf'],
            'phone' => ['nullable', 'string', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'cep' => ['required', 'string', 'size:9', 'regex:/^\d{5}-\d{3}$/'],
            'uf' => 'required|string|size:2',
            'city' => 'required|string|max:30',
            'neighborhood' => 'required|string|max:40',
            'street' => 'required|string|max:100',
        ]);

        $invite = Invite::where('token', $request->token)->first();

        if (!$invite || now()->greaterThan($invite->expires_at)) {
            return response()->json(['error' => 'Token inválido ou expirado.'], 400);
        }

        $employee = Employee::create([
            'name' => $request->name,
            'email' => $invite->email,
            'cpf' => $request->cpf,
            'phone' => $request->phone,
            'cep' => $request->cep,
            'uf' => $request->uf,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street
        ]);

        User::create([
            'name' => $request->name,
            'email' => $invite->email,
            'employee_id' => $employee->id,
            'id_profile' => 3,
            'password' => null,
        ]);

        $invite->update(['status' => 'Finalizado']);

        return response()->json(['message' => 'Cadastro realizado com sucesso!']);
    }


    public function getAddressByCep($cep) {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->failed() || isset($response['erro'])) {
            return response()->json(['message' => 'CEP inválido ou não encontrado.'], 400);
        }

        return response()->json([
            'uf' => $response['uf'],
            'city' => $response['localidade'],
            'neighborhood' => $response['bairro'],
            'street' => $response['logradouro']
        ]);
    }
}