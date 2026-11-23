@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="tasks-page">
        <div class="page-heading">
            <div>
                <p class="page-eyebrow">Your workspace</p>
                <h1 class="page-title">Tasks</h1>
                <p class="page-description">
                    Keep your work, school, and personal plans together in one place.
                </p>
            </div>

            <span class="page-status">
                Getting started
            </span>
        </div>

        <section class="empty-state" aria-labelledby="empty-state-title">
            <div class="empty-state-content">
                <span class="empty-state-icon">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                        <path d="M8 5.75h8M8 10.25h8M8 14.75h4m-7.25-12h14.5A1.75 1.75 0 0 1 19 4.5v15a1.75 1.75 0 0 1-1.75 1.75H6.75A1.75 1.75 0 0 1 5 19.5v-15a1.75 1.75 0 0 1 1.75-1.75Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                    </svg>
                </span>
                <h2 id="empty-state-title" class="empty-state-title">No tasks yet</h2>
                <p class="empty-state-description">
                    Your task list is ready. We’ll connect it to categories and saved tasks as development continues.
                </p>
            </div>
        </section>
    </div>
@endsection
