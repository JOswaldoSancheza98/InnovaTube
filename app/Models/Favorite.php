<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = ['user_id', 'video_id', 'title', 'thumbnail'];

    // Relación con el modelo User (un favorito pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}