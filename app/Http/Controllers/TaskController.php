<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('overview');
    }

    public function overview(): View
    {
        $tasks = auth()->user()->tasks()
            ->with('category')
            ->get();

        $categories = Category::withCount([
            'tasks' => fn ($query) => $query->where('user_id', auth()->id()),
        ])
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
