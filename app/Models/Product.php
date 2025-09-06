<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Essa entidade foi criada somente como fallback, caso o cache tenha expirado e a API retorne algum erro
// Assim temos o máximo de disponibilidade 
// Mesmo que duplique um pouco os dados, creio que o trade-off vale a pena
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price_in_cents',
        'fakestore_product_id',
        'image_url',
    ];
}
