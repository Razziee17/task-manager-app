@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">Your workspace</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Tasks</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                    Keep your work, school, and personal plans together in one place.
                </p>
            </div>

            <span class="rounded-full bg-white px-3.5 py-2 text-sm font-medium text-slate-600 ring-1 ring-slate-200">
                Getting started
            </span>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm sm:p-12" aria-labelledby="empty-state-title">
            <div class="mx-auto flex max-w-lg flex-col items-center text-center">
                <span class="mb-5 grid size-14 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                    <svg aria-hidden="true" class="size-7" viewBox="0 0 24 24" fill="none">
                        <path d="M8 5.75h8M8 10.25h8M8 14.75h4m-7.25-12h14.5A1.75 1.75 0 0 1 19 4.5v15a1.75 1.75 0 0 1-1.75 1.75H6.75A1.75 1.75 0 0 1 5 19.5v-15a1.75 1.75 0 0 1 1.75-1.75Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"/>
                    </svg>
                </span>
                <h2 id="empty-state-title" class="text-lg font-semibold text-slate-950">No tasks yet</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Your task list is ready. We’ll connect it to categories and saved tasks as development continues.
                </p>
            </div>
        </section>
    </div>
@endsection
