@extends('tailwind-builder::layouts.builder', ['title' => 'Create Builder Page'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost btn-sm">
            ← Back to pages
        </a>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="mb-6">
                <h1 class="text-2xl font-bold">Create Page</h1>
                <p class="text-sm text-base-content/70">
                    Create a new builder-managed page.
                </p>
            </div>

            <form method="POST" action="{{ route('tailwind-builder.pages.store') }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Title</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" class="input input-bordered"
                            required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Slug</span>
                        </label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="input input-bordered"
                            placeholder="Auto-generated if empty">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Type</span>
                        </label>
                        <select name="type" class="select select-bordered">
                            @foreach ($types as $type)
                                <option value="{{ $type }}" @selected(old('type', 'page') === $type)>
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
                                <option value="{{ $status }}" @selected(old('status', 'draft') === $status)>
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
                            @checked(old('show_in_navigation'))>
                        <span class="label-text">Show in navigation</span>
                    </label>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Navigation Label</span>
                        </label>
                        <input type="text" name="navigation_label" value="{{ old('navigation_label') }}"
                            class="input input-bordered" placeholder="Defaults to title">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Navigation Order</span>
                        </label>
                        <input type="number" min="0" name="navigation_order"
                            value="{{ old('navigation_order', 0) }}" class="input input-bordered">
                    </div>
                </div>

                <div class="divider">SEO</div>

                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">SEO Title</span>
                        </label>
                        <input type="text" name="seo_title" value="{{ old('seo_title') }}" class="input input-bordered">
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">SEO Description</span>
                        </label>
                        <textarea name="seo_description" rows="3" class="textarea textarea-bordered">{{ old('seo_description') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost">
                        Cancel
                    </a>

                    <button class="btn btn-primary">
                        Create Page
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
