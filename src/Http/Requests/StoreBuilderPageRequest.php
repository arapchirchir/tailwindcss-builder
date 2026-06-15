<?php

namespace Tecworld\TailwindBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageType;

class StoreBuilderPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', Rule::in(BuilderPageType::values())],
            'status' => ['nullable', 'string', Rule::in(BuilderPageStatus::values())],

            'content_json' => ['nullable', 'array'],

            'show_in_navigation' => ['nullable', 'boolean'],
            'navigation_label' => ['nullable', 'string', 'max:255'],
            'navigation_order' => ['nullable', 'integer', 'min:0'],
            'navigation_parent_id' => ['nullable', 'integer', 'min:1'],

            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'seo_image' => ['nullable', 'string', 'max:2048'],

            'is_homepage' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'show_in_navigation' => $this->boolean('show_in_navigation'),
            'is_homepage' => $this->boolean('is_homepage'),
        ]);
    }
}
