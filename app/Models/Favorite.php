<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'product_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
