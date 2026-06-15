@extends('tailwind-builder::layouts.builder', ['title' => 'Edit Builder Page'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost btn-sm">
            ← Back to pages
        </a>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Edit Page</h1>
                    <p class="text-sm text-base-content/70">
                        Update page details, navigation visibility, SEO, and content JSON.
                    </p>
                </div>

                <form method="POST" action="{{ route('tailwind-builder.pages.destroy', $page) }}"
                    onsubmit="return confirm('Delete this page? This action can be restored only from the database/revisions for now.');">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-error btn-sm" @disabled($page->is_system)>
                        Delete
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('tailwind-builder.pages.update', $page) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Title</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}"
                            class="input input-bordered" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Slug</span>
                        </label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug) }}"
                            class="input input-bordered" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Type</span>
                        </label>
                        <select name="type" class="select select-bordered">
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected(old('type', $page->type) === $type)>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered">
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(old('status', $page->status) === $status)>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="divider">Navigation</div>

                <div class="grid gap-6 md:grid-cols-3">
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="show_in_navigation" value="1" class="checkbox checkbox-primary"
                            @checked(old('show_in_navigation', $page->show_in_navigation))>
                        <span class="label-text">Show in navigation</span>
                    </label>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Navigation Label</span>
                        </label>
                        <input type="text" name="navigation_label"
                            value="{{ old('navigation_label', $page->navigation_label) }}" class="input input-bordered"
                            placeholder="Defaults to title">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Navigation Order</span>
                        </label>
                        <input type="number" min="0" name="navigation_order"
                            value="{{ old('navigation_order', $page->navigation_order) }}" class="input input-bordered">
                    </div>
                </div>

                <div class="divider">SEO</div>

                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">SEO Title</span>
                        </label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}"
                            class="input input-bordered">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">SEO Description</span>
                        </label>
                        <textarea name="seo_description" rows="3" class="textarea textarea-bordered">{{ old('seo_description', $page->seo_description) }}</textarea>
                    </div>
                </div>

                <div class="divider">Content JSON</div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Page Content</span>
                        <span class="label-text-alt">Temporary JSON editor before visual builder</span>
                    </label>

                    <textarea name="content_json_raw" rows="18" class="textarea textarea-bordered font-mono text-sm"
                        spellcheck="false">{{ old('content_json_raw', json_encode($page->content_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost">
                        Cancel
                    </a>

                    <button class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
