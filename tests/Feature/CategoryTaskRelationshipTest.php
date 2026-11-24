<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTaskRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_and_tasks_are_related_to_users(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Work']);
        $task = Task::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test task',
            'description' => 'Test description',
            'status' => 'pending',
            'due_date' => '2026-12-01',
        ]);

        $this->assertTrue($user->tasks()->whereKey($task->id)->exists());
        $this->assertTrue($category->tasks()->whereKey($task->id)->exists());
        $this->assertSame($user->id, $task->user->id);
        $this->assertSame('Work', $task->category->name);

        $this->get('/')
            ->assertOk();
    }
}
