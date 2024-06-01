<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Ramsey\Uuid\Guid\Fields;

class StorePostRequest extends FormRequest
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
            'title' => ['max:15', 'string', 'nullable'],
            'body' => ['required', 'string'],
            'attachments' => ['array', 'max:6'],
            'attachments.*' => [
                File::image()
                    ->min('1kb')
                    ->max('25mb')
            ],
            'user_id' => ['numeric']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(
            [
                'user_id' => auth()->user()->id,
            ]
        );
    }
}
