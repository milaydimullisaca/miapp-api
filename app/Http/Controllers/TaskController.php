<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // LISTAR
    public function index()
    {
        return response()->json(Task::all());
    }

    // CREAR
    public function store(Request $request)
    {
        $task = Task::create([
           'title' => $request->title,
           'description' => $request->description,
           'image' => $request->image,
           'latitude' => $request->latitude,
           'longitude' => $request->longitude,
        ]);

        return response()->json($task, 201);
    }

    // MOSTRAR UNO
    public function show(string $id)
    {
        return response()->json(
            Task::findOrFail($id)
        );
    }

    // EDITAR
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        $task->update($request->all());

        return response()->json($task);
    }

    // ELIMINAR
    public function destroy(string $id)
    {
        Task::destroy($id);

        return response()->json([
            'message' => 'Task eliminada'
        ]);
    }
}