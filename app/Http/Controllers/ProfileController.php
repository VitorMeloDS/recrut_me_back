<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuProfile;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'menus' => ['required', 'array'],
            'menus.*' => ['exists:menu,id'],
        ];

        $messages = [
            'name.required' => 'O nome do perfil é obrigatório.',
            'menus.required' => 'O menu é obrigatório.',
            'menus.*.exists' => 'Um dos menus informados não existe.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $profile = Profile::create(['name' => $request->name]);

        foreach ($request->menus as $menuId) {
            MenuProfile::create([
                'id_menu' => $menuId,
                'id_profile' => $profile->id,
            ]);
        }

        return response()->json(['data' => 'Perfil criado com sucesso!'], Response::HTTP_CREATED);
    }

    public function index()
    {
        $profile = Profile::get();
        return response()->json([ 'data' => $profile]);
    }

    public function show(string $id)
    {
        $profile = Profile::with('menus')->find($id);
        return response()->json([ 'data' => $profile], Response::HTTP_OK);
    }

    /**
     * Update an existing resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Definir regras de validação
        $rules = [
            'name' => ['required'],
            'menus' => ['required', 'array'],
            'menus.*' => ['exists:menu,id'],
        ];

        // Mensagens de erro personalizadas
        $messages = [
            'name.required' => 'O nome do perfil é obrigatório.',
            'menus.required' => 'O menu é obrigatório.',
            'menus.*.exists' => 'Um dos menus informados não existe.',
        ];

        // Validar a requisição
        $validator = Validator::make($request->all(), $rules, $messages);

        // Se a validação falhar
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Procurar o perfil pelo ID
        $profile = Profile::find($id);

        // Se o perfil não for encontrado
        if (!$profile) {
            return response()->json([
                'error' => 'Perfil não encontrado.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Atualiza o nome
        $profile->update(['name' => $request->name]);

        // Remove os menus antigos
        $profile->menus()->detach();

        // Adiciona os novos menus
        $profile->menus()->attach($request->menus);

        // Retornar resposta de sucesso
        return response()->json(['data' => 'Perfil atualizado com sucesso!'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Profile::destroy($id);
        return response()->json(['data' => 'Perfil deletado.'], Response::HTTP_OK);
    }
}