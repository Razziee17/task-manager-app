<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_api_requires_authentication(): void
    {
        $this->getJson(route('api.tasks.index'))
            ->assertUnauthorized();
    }

    public function test_task_list_only_returns_tasks_owned_by_the_signed_in_user(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $category = Category::create(['name' => 'Work']);
        $ownTask = Task::factory()->for($user)->for($category)->create(['title' => 'My task']);
        Task::factory()->for($anotherUser)->for($category)->create(['title' => 'Private task']);

        $this->actingAs($user)
            ->getJson(route('api.tasks.index'))
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownTask->id)
            ->assertJsonPath('data.0.category.name', 'Work')
            ->assertJsonMissing(['title' => 'Private task']);
    }

    public function test_category_list_includes_only_the_signed_in_users_task_counts(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $category = Category::create(['name' => 'Work']);
        Task::factory()->for($user)->for($category)->count(2)->create();
        Task::factory()->for($anotherUser)->for($category)->create();

        $this->actingAs($user)
            ->getJson(route('api.categories.index'))
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Work')
            ->assertJsonPath('data.0.tasks_count', 2);
    }

    public function test_user_can_create_a_task_and_invalid_values_are_rejected(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'School']);

        $this->actingAs($user)
            ->postJson(route('api.tasks.store'), [
                'title' => 'Finish project',
                'description' => 'Complete the API',
                'category_id' => $category->id,
                'due_date' => '2026-12-01',
            ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.category.name', 'School');

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Finish project',
            'category_id' => $category->id,
        ]);

        $this->postJson(route('api.tasks.store'), [
            'title' => '',
            'category_id' => 999,
            'due_date' => 'not-a-date',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'category_id', 'due_date']);
    }

    public function test_user_can_complete_and_delete_owned_tasks_but_not_another_users_task(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $category = Category::create(['name' => 'Personal']);
        $ownTask = Task::factory()->for($user)->for($category)->create(['status' => 'pending']);
        $privateTask = Task::factory()->for($anotherUser)->for($category)->create();

        $this->actingAs($user)
            ->patchJson(route('api.tasks.update', $ownTask), ['status' => 'done'])
            ->assertOk()
            ->assertJsonPath('data.status', 'done');

        $this->deleteJson(route('api.tasks.destroy', $privateTask))
            ->assertNotFound();

        $this->assertDatabaseHas('tasks', ['id' => $privateTask->id]);

        $this->deleteJson(route('api.tasks.destroy', $ownTask))
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $ownTask->id]);
    }
}
