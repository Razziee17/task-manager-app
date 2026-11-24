@extends('layouts.app')

@section('title', 'Overview')

@section('content')
    <div class="tasks-page">
        <div class="page-heading">
            <div>
                <p class="page-eyebrow">Today</p>
                <h1 class="page-title">Overview</h1>
                <p class="page-description">See a quick summary of your task list.</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="primary-button">View tasks</a>
        </div>

        <section class="summary-grid" aria-label="Task summary">
            <article class="summary-card">
                <p class="summary-label">All tasks</p>
                <strong class="summary-value" id="overview-all-count">{{ $tasks->count() }}</strong>
            </article>
            <article class="summary-card">
                <p class="summary-label">Pending</p>
                <strong class="summary-value" id="overview-pending-count">{{ $pendingCount }}</strong>
            </article>
            <article class="summary-card">
                <p class="summary-label">Completed</p>
                <strong class="summary-value" id="overview-done-count">{{ $doneCount }}</strong>
            </article>
        </section>

        <section class="category-panel" aria-labelledby="category-title">
            <div class="task-panel-heading">
                <div>
                    <p class="panel-eyebrow">Organization</p>
                    <h2 id="category-title" class="panel-title">Categories</h2>
                </div>
                <span class="task-count">{{ $categories->count() }} categories</span>
            </div>
            <div class="category-grid">
                @foreach ($categories as $category)
                    <a href="{{ route('tasks.index') }}" class="category-card">
                        <span>
                            <strong>{{ $category->name }}</strong>
                            <small>{{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }}</small>
                        </span>
                        <span aria-hidden="true">→</span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
