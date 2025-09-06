<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory, HasUuids, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'remember_token',
        "deleted_at",
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
