<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'description',
    ];

    // Relacja odwrotna z User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
