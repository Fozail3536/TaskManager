<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        // Admin can view/manage all tasks, users can only see/manage their own tasks
        $tasks = Task::when(Auth::user()->role !== 'admin', function ($query) {
            $query->where('user_id', Auth::id());
        })->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })->get();

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Task created successfully']);
    }

    public function destroy($id)
    {
        $task = Task::where('id', $id)->when(Auth::user()->role !== 'admin', function ($query) {
            $query->where('user_id', Auth::id());
        })->firstOrFail();

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function complete($id)
    {
        $task = Task::where('id', $id)->when(Auth::user()->role !== 'admin', function ($query) {
            $query->where('user_id', Auth::id());
        })->firstOrFail();

        $task->update(['status' => 'completed']);
        return response()->json(['message' => 'Task marked as complete']);
    }
}
