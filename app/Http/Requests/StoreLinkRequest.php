<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'url' => 'required|url|unique:links,url,NULL,id,user_id,' . auth()->id(),
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array|max:5',
            'tags.*' => 'string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The link title is required.',
            'url.required' => 'The URL is required.',
            'url.url' => 'Please provide a valid URL.',
            'url.unique' => 'You already have this URL in your links.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'tags.max' => 'You can add maximum 5 tags.',
            'tags.*.max' => 'Each tag cannot exceed 50 characters.',
        ];
    }
}
