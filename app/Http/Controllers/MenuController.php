<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::whereHas('profiles')->whereHas('profiles.user')->get();

        return response()->json(['data' => $menus]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}