@extends('tailwind-builder::layouts.builder', ['title' => 'Builder Pages'])

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Pages</h1>
            <p class="text-sm text-base-content/70">
                Create and manage builder pages.
            </p>
        </div>

        <a href="{{ route('tailwind-builder.pages.create') }}" class="btn btn-primary">
            Create Page
        </a>
    </div>

    <form method="GET" action="{{ route('tailwind-builder.pages.index') }}" class="card mb-6 bg-base-100 shadow">
        <div class="card-body grid gap-4 md:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pages..."
                class="input input-bordered w-full md:col-span-2">

            <select name="status" class="select select-bordered w-full">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <select name="type" class="select select-bordered w-full">
                <option value="">All types</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>

            <div class="md:col-span-4 flex gap-2">
                <button class="btn btn-neutral">Filter</button>
                <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="card bg-base-100 shadow">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Navigation</th>
                            <th>Updated</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pages as $page)
                            <tr>
                                <td>
                                    <div class="font-medium">{{ $page->title }}</div>
                                    @if ($page->is_homepage)
                                        <div class="badge badge-primary badge-sm mt-1">Homepage</div>
                                    @endif
                                </td>
                                <td>
                                    <code class="text-xs">{{ $page->slug }}</code>
                                </td>
                                <td>{{ ucfirst($page->type) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $page->status === 'published' ? 'badge-success' : 'badge-ghost' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($page->show_in_navigation)
                                        <span class="badge badge-info">Enabled</span>
                                    @else
                                        <span class="badge badge-ghost">Disabled</span>
                                    @endif
                                </td>
                                <td>{{ $page->updated_at?->diffForHumans() }}</td>
                                <td class="text-right">
                                    <a href="{{ route('tailwind-builder.pages.edit', $page) }}"
                                        class="btn btn-sm btn-ghost">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-base-content/60">
                                    No pages found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pages->hasPages())
                <div class="border-t border-base-300 p-4">
                    {{ $pages->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
