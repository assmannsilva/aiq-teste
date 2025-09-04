<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateClientFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $client = $this->user();
        $clientId = $this->route('id');

        return $client && $client->id === $clientId;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('id');
        return [
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
            'name' => 'nullable|string|max:255|min:3',
        ];
    }

    public function after()
    {
        return [
            function (Validator $validator) {
                if (!$this->filled("email") && !$this->filled("name")) {
                    $validator->errors()->add("data", "At least one field must be provided to update the client.");
                }
            }
        ];
    }
}
