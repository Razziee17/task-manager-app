<?php

namespace App\Http\Controllers;

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
        return view('tasks.overview');
    }

    public function tasks(): View
    {
        return view('tasks.index');
    }
}
