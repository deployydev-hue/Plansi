<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', Rule::in(['pending', 'completed'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', $this->user()->id),
            ],
            'due_date' => ['nullable', Rule::in(['today', 'overdue', 'upcoming', 'no_due'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'due_soon', 'priority_high'])],
        ];
    }
}
