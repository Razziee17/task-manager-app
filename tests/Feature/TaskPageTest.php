<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_tasks_pages_render_the_task_layout(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertRedirect(route('overview'));

        $this->get(route('overview'))
            ->assertOk()
            ->assertViewIs('tasks.overview')
            ->assertSee('Overview');

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index');
    }

    public function test_overview_only_shows_the_signed_in_users_tasks(): void
    {
        $signedInUser = User::factory()->create();
        $anotherUser = User::factory()->create();
        $category = Category::create(['name' => 'Work']);

        Task::create([
            'user_id' => $anotherUser->id,
            'category_id' => $category->id,
            'title' => 'Another users private task',
            'description' => null,
            'status' => 'pending',
            'due_date' => '2026-12-01',
        ]);

        $this->actingAs($signedInUser)
            ->get(route('overview'))
            ->assertOk()
            ->assertSee('0')
            ->assertDontSee('Another users private task');
    }
}
