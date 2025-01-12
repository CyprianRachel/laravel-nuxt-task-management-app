<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // jeśli kiedyś potrzebujesz weryfikacji email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Pola, które można masowo przypisywać (mass assignment).
     * Dzięki temu np. User::create([...]) zadziała.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'role',
    ];

    /**
     * Pola, które powinny być ukryte podczas serializacji (np. w JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
        // ewentualnie 'pivot', jeśli używasz relacji many-to-many
    ];

    /**
     * Rzutowania kolumn na konkretne typy.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed' // w Laravel 10+ możesz odkomentować, by użyć hashowania
    ];

    /**
     * Relacja do modelu Organization.
     * user.organization_id -> organizations.id
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getTasks()
{
    $tasks = Task::with(['orderItems.product'])->get(); // Pobieramy zadania wraz z produktami przypisanymi do zamówienia
    return response()->json($tasks);
}

}
