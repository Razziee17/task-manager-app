<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Contracts\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        return $this->overview();
    }

    public function overview(): View
    {
        $tasks = Task::with('category')
            ->get();

        $categories = Category::withCount('tasks')
            ->orderBy('name')
            ->get();

        return view('tasks.overview', [
            'tasks' => $tasks,
            'categories' => $categories,
            'pendingCount' => $tasks->where('status', 'pending')->count(),
            'doneCount' => $tasks->where('status', 'done')->count(),
        ]);
    }

    public function tasks(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('tasks.index', [
            'categories' => $categories,
        ]);
    }
}
