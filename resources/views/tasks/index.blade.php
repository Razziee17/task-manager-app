@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="tasks-page">
        <div class="page-heading">
            <div>
                <p class="page-eyebrow">Task workspace</p>
                <h1 class="page-title">Tasks</h1>
                <p class="page-description">Create and organize your tasks for today.</p>
            </div>
            <button type="button" class="primary-button" id="open-task-modal">
                <span aria-hidden="true">+</span>
                Add task
            </button>
        </div>

        <section class="task-panel" aria-labelledby="task-list-title">
            <div class="task-panel-heading">
                <div>
                    <p class="panel-eyebrow">Task list</p>
                    <h2 id="task-list-title" class="panel-title">All tasks</h2>
                </div>
                <span class="task-count" id="task-count">0 items</span>
            </div>

            <div class="task-toolbar">
                <label class="search-field">
                    <span class="screen-reader-only">Search tasks</span>
                    <input id="task-search" type="search" placeholder="Search tasks">
                </label>
                <label class="filter-field">
                    <span class="screen-reader-only">Filter by category</span>
                    <select id="category-filter">
                        <option value="all">All categories</option>
                    </select>
                </label>
                <label class="filter-field">
                    <span class="screen-reader-only">Filter by status</span>
                    <select id="status-filter">
                        <option value="all">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="done">Completed</option>
                    </select>
                </label>
            </div>

            <div class="task-table-wrap">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Due date</th>
                            <th><span class="screen-reader-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody id="task-list"></tbody>
                </table>
                <div id="empty-task-list" class="empty-state">
                    <span class="empty-state-icon" aria-hidden="true">✓</span>
                    <h2 class="empty-state-title">No tasks yet</h2>
                    <p class="empty-state-description">Add a task to start planning your work.</p>
                </div>
                <p id="no-filtered-results" class="no-results" hidden>No tasks match the selected filters.</p>
                <div id="task-pagination" class="task-pagination" hidden>
                    <span id="task-page-status" aria-live="polite"></span>
                    <div class="task-pagination-actions">
                        <button type="button" class="secondary-button" id="previous-task-page">Previous</button>
                        <button type="button" class="secondary-button" id="next-task-page">Next</button>
                    </div>
                </div>
                <p id="task-api-error" class="api-error" role="alert" hidden></p>
            </div>
        </section>
    </div>

    <div class="modal-backdrop" id="task-modal" hidden>
        <div class="task-modal" role="dialog" aria-modal="true" aria-labelledby="task-modal-title">
            <div class="modal-heading">
                <h2 id="task-modal-title">Add task</h2>
                <button type="button" class="icon-button" id="close-task-modal" aria-label="Close">×</button>
            </div>
            <form id="task-form" novalidate>
                <input type="hidden" id="task-id">
                <label class="form-field">
                    <span>Title</span>
                    <input id="task-title" type="text" maxlength="100" required>
                    <small class="field-error" id="title-error"></small>
                </label>
                <label class="form-field">
                    <span>Description</span>
                    <textarea id="task-description" rows="3" maxlength="500"></textarea>
                </label>
                <div class="form-grid">
                    <label class="form-field">
                        <span>Category</span>
                        <select id="task-category" required>
                            <option value="">Select a category</option>
                        </select>
                    </label>
                    <label class="form-field">
                        <span>Status</span>
                        <select id="task-status">
                            <option value="pending">Pending</option>
                            <option value="done">Completed</option>
                        </select>
                    </label>
                </div>
                <label class="form-field">
                    <span>Due date</span>
                    <input id="task-due-date" type="date" required>
                    <small class="field-error" id="date-error"></small>
                </label>
                <p id="task-form-error" class="api-error" role="alert" hidden></p>
                <div class="modal-actions">
                    <button type="button" class="secondary-button" id="cancel-task">Cancel</button>
                    <button type="submit" class="primary-button">Save task</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-backdrop" id="task-confirmation-modal" hidden>
        <div class="confirmation-modal" role="alertdialog" aria-modal="true" aria-labelledby="task-confirmation-title" aria-describedby="task-confirmation-message">
            <div class="modal-heading">
                <h2 id="task-confirmation-title">Confirm action</h2>
                <button type="button" class="icon-button" id="close-task-confirmation" aria-label="Cancel">×</button>
            </div>
            <p class="confirmation-message" id="task-confirmation-message"></p>
            <div class="modal-actions">
                <button type="button" class="secondary-button" id="cancel-task-confirmation">Cancel</button>
                <button type="button" class="primary-button" id="confirm-task-action">Continue</button>
            </div>
        </div>
    </div>
@endsection
