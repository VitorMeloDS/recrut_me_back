<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::get();
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