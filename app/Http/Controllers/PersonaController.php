<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        return Persona::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:personas,email',
        ]);

        return Persona::create($request->all());
    }

    public function show(Persona $persona)
    {
        return $persona;
    }

    public function update(Request $request, Persona $persona)
    {
        $persona->update($request->all());
        return $persona;
    }

    public function destroy(Persona $persona)
    {
        $persona->delete();
        return response()->json(null, 204);
    }
}