<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_tasks_pages_render_the_task_layout(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('tasks.overview')
            ->assertSee('Overview');

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index');
    }
}
