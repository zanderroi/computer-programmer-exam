<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = auth()->user();
        $status = $request->input('status');

        $tasksQuery = Task::with(['creator', 'assignedUsers'])
            ->where(function ($query) use ($user) {
                $query->where('creator_id', $user->id)
                    ->orWhereHas('assignedUsers', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });


        if ($status) {
            $tasksQuery->where('status', $status);
        }

        $tasks = $tasksQuery->get();

        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'creator_id' => 'required|exists:users,id',
            // 'assigned_user_ids' => 'nullable|array',
            // 'assigned_user_ids.*' => 'exists:users,id',
            'file' => 'nullable|mimes:png,pdf|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('files', 'public');

            $validated['file'] = $filePath;
        }
      
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'creator_id' => $user->id,
            'file' => $filePath ?? null
        ]);

      
        // if (!empty($validated['assigned_user_ids'])) {
        //     $task->assignedUsers()->attach($validated['assigned_user_ids']);
        // }

        return response()->json($task, 201);
     
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
        $task = Task::with(['creator', 'assignedUsers'])->findOrFail($task);
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $task)
    {

        $task = Task::findOrFail($task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_ids' => 'nullable|array',
            'assigned_user_ids.*' => 'exists:users,id',
        ]);

       
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if (isset($validated['assigned_user_ids'])) {
            $task->assignedUsers()->sync($validated['assigned_user_ids']);
        }

        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
