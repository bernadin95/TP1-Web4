<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Critic extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id','film_id','score','comment','created_at','updated_at'
    ];

    public function film() {
        return $this->belongsTo(Film::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}