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
        'user_id' => auth()->id(),
    ]);

    return response()->json($task);
}

    // MOSTRAR UNO
    public function show(string $id)
    {
        return response()->json(Task::findOrFail($id));
    }

    // ACTUALIZAR
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json($task);
    }

    // ELIMINAR
    public function destroy(string $id)
    {
        Task::destroy($id);

        return response()->json(['message' => 'Task eliminada']);
    }
}