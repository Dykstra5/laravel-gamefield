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
            'body' => ['required', 'string', 'max:1000'],
            'attachments' => ['array', 'max:6'],
            'attachments.*' => [
                File::image()
                    ->max('20mb')
            ],
            'user_id' => ['numeric'],
            'tags' => ['array', 'min:1', 'max:3'],
            'tags.*.type' => ['required', 'string'],
            'tags.*.tag_id' => ['required', 'numeric'],
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
            'body.required' => 'El contenido no puede estar vacío',
            'body.max' => 'El contenido no puede tener más de 1000 carácteres',
            'tags.min' => 'Debes añadir mínimo 1 tema',
            'tags.max' => 'Debe tener máximo 3 temas',
            'title' => 'El título no puede contener más de 15 carácteres',
        ];
    }
}
