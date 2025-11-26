<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','release_year','length','description',
        'rating','language_id','special_features','image','created_at'
    ];

    public function language() {
        return $this->belongsTo(Language::class);
    }

    public function actors() {
        return $this->belongsToMany(Actor::class);
    }

    public function critics() {
        return $this->hasMany(Critic::class);
    }
}
