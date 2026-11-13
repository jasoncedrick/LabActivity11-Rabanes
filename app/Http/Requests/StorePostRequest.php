<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->author !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:20'],
            'category' => ['required', 'in:Announcement,Health,Peace & Order,Ayuda,Education'],
            'tags' => ['required', 'array', 'min:1'],
            'tags.*' => ['exists:tags,id'],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.required' => 'Pick at least one tag.',
            'tags.*.exists' => 'One of the selected tags does not exist.',
        ];
    }
}
