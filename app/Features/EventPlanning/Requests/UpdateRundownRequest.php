<?php

namespace App\Features\EventPlanning\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRundownRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['sometimes', 'required', 'date'],
            'status' => ['nullable', 'string', 'in:draft,published,completed,cancelled'],
            'notes' => ['nullable', 'string'],
            'is_public' => ['nullable', 'boolean'],
        ];
    }
}