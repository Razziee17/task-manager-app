<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskApiController extends Controller
{
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->select(['id', 'name'])
            ->withCount(['tasks' => fn ($query) => $query->where('user_id', $request->user()->id)])
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()->tasks()
            ->with('category:id,name')
            ->latest()
            ->get();

        return response()->json([
            'data' => $tasks->map(fn (Task $task) => $this->taskData($task)),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());
        $validated['status'] ??= 'pending';

        $task = $request->user()->tasks()->create($validated)->load('category:id,name');

        return response()->json(['data' => $this->taskData($task)], 201);
    }

    public function show(Request $request, int $task): JsonResponse
    {
        $task = $request->user()->tasks()
            ->with('category:id,name')
            ->findOrFail($task);

        return response()->json(['data' => $this->taskData($task)]);
    }

    public function update(Request $request, int $task): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($task);
        $task->update($request->validate($this->rules(true)));
        $task->load('category:id,name');

        return response()->json(['data' => $this->taskData($task)]);
    }

    public function destroy(Request $request, int $task): JsonResponse
    {
        $request->user()->tasks()->findOrFail($task)->delete();

        return response()->json(null, 204);
    }

    private function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes|required' : 'required';

        return [
            'title' => [$required, 'string', 'max:100'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'category_id' => [$required, 'integer', 'exists:categories,id'],
            'status' => ['sometimes', 'required', Rule::in(['pending', 'done'])],
            'due_date' => [$required, 'date_format:Y-m-d'],
        ];
    }

    private function taskData(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'due_date' => $task->due_date->toDateString(),
            'category_id' => $task->category_id,
            'category' => [
                'id' => $task->category->id,
                'name' => $task->category->name,
            ],
        ];
    }
}
