<?php

namespace App\Models;
use App\Models\Convert_table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Convert_table::class);
    }

    
}
