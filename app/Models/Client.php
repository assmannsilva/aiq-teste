<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasUuids, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
