<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    protected $fillable = [
        'pair_id',
        'request_count',
    ];

    //relation entre la table des requêtes et la table des conversions
    public function pair()
    {
        return $this->belongsTo(Convert::class);
    }

    
}
