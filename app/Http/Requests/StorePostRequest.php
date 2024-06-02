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
                    ->max('20mb')
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

    public function messages ()
    {
        return [
            'attachments.*.image' => 'Solo se puede publicar imágenes',
            'attachments.*.max' => 'El tamaño de la imagen no puede ser superior a 20Mb',
            'body' => 'El contenido no puede estar vacío',
            'title' => 'El título no puede contener más de 15 carácteres'
        ];
    }
}
