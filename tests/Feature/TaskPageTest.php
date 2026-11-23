<?php

namespace Tests\Feature;

use Tests\TestCase;

class TaskPageTest extends TestCase
{
    public function test_dashboard_and_tasks_pages_render_the_task_layout(): void
    {
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('tasks.index')
            ->assertSee('Your workspace')
            ->assertSee('No tasks yet');

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index');
    }
}
