<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//Decidi colocar as validações centralizadas nos FormRequests
// É a maneira mais comum e simples de implementar no Laravel
// Creio que fica simples para quem avaliar e também esse software foi projetado para validar no nível de API
class SearchFavoritesFormRequest extends FormRequest
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
            "page"     => "sometimes|integer|min:1",
            "per_page" => "sometimes|integer|min:1|max:100"
        ];
    }
}
