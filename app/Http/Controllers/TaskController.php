<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // LISTAR POR USUARIO
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = Task::where('user_id', $user->id)->get();

        return response()->json($tasks);
    }

    // CREAR
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image' => $imagePath,
            'scheduled_at' => $request->scheduled_at,
            'user_id' => auth()->id(),

            // 🔥 IMPORTANTE
            'is_done' => false,
        ]);

        return response()->json($task, 201);
    }

    // MOSTRAR UNO
    public function show(string $id)
    {
        return response()->json(Task::findOrFail($id));
    }

    // ACTUALIZAR (EDIT NORMAL)
    public function update(Request $request, string $id)
{
    try {

        $task = Task::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$task) {

            return response()->json([
                'message' => 'Task no encontrada'
            ], 404);
        }

        // 🔥 SI HAY NUEVA IMAGEN
        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('tasks', 'public');

            $task->image = $imagePath;
        }

        $task->title = $request->title;

        $task->description = $request->description;

        $task->latitude = $request->latitude;

        $task->longitude = $request->longitude;

        $task->scheduled_at = $request->scheduled_at;

        $task->save();

        return response()->json($task);

    } catch (\Throwable $e) {

        return response()->json([
            'message' => 'Error al actualizar',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // 🔥 NUEVO: CAMBIAR CHECKBOX
   public function toggleStatus($id)
{
    try {

        $task = Task::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$task) {

            return response()->json([
                'message' => 'Task no encontrada'
            ], 404);
        }

        // 🔥 CAMBIAR 0 ↔ 1
        $task->is_done = $task->is_done ? 0 : 1;

        $task->save();

        return response()->json([
            'success' => true,
            'is_done' => $task->is_done
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'message' => 'Error toggle',
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ], 500);
    }
}

    // ELIMINAR
    public function destroy(string $id)
    {
        Task::destroy($id);

        return response()->json(['message' => 'Task eliminada']);
    }
}