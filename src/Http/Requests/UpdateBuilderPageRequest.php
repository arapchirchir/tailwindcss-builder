<?php

namespace Tecworld\TailwindBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageType;

class UpdateBuilderPageRequest extends FormRequest
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
            'content_json_raw' => ['nullable', 'string'],

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
        $contentJson = $this->input('content_json');

        if ($this->filled('content_json_raw')) {
            $decoded = json_decode((string) $this->input('content_json_raw'), true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $contentJson = $decoded;
            }
        }

        $this->merge([
            'content_json' => $contentJson,
            'show_in_navigation' => $this->boolean('show_in_navigation'),
            'is_homepage' => $this->boolean('is_homepage'),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('content_json_raw')) {
                return;
            }

            json_decode((string) $this->input('content_json_raw'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $validator->errors()->add(
                    'content_json_raw',
                    'The content JSON is invalid: '.json_last_error_msg()
                );
            }
        });
    }
}
