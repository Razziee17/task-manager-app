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
                <strong class="summary-value" id="overview-all-count">0</strong>
            </article>
            <article class="summary-card">
                <p class="summary-label">Pending</p>
                <strong class="summary-value" id="overview-pending-count">0</strong>
            </article>
            <article class="summary-card">
                <p class="summary-label">Completed</p>
                <strong class="summary-value" id="overview-done-count">0</strong>
            </article>
        </section>

        <section class="category-panel" aria-labelledby="category-title">
            <div class="task-panel-heading">
                <div>
                    <p class="panel-eyebrow">Organization</p>
                    <h2 id="category-title" class="panel-title">Categories</h2>
                </div>
                <span class="task-count" id="overview-category-count">0 categories</span>
            </div>
            <div class="category-grid" id="overview-category-list"></div>
            <div id="overview-category-empty" class="empty-state" hidden>
                <p class="empty-state-description">No categories are available yet.</p>
            </div>
            <p id="overview-api-error" class="api-error" role="alert" hidden></p>
        </section>
    </div>
@endsection
