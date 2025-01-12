<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'status'];

    // Relacja z OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relacja z Organization
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    
}
