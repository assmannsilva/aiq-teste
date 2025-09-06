<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// As validações foram unificadas nos FormRequests, inclusive unique de clientes
class StoreFavoriteFormRequest extends FormRequest
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
        $clientId = $this->user()->id;
        return [
            "product_id" => [
                "required",
                "numeric",
                Rule::unique("favorites", "fake_store_product_id")->where(fn($query) => $query->where("client_id", $clientId))
            ]
        ];
    }
}
