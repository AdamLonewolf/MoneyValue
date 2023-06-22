<?php

namespace App\Models;

use App\Models\Requests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Convert_table extends Model
{
    use HasFactory;

    
    protected $table = "convert_tables";
    protected $primaryKey = "id";
    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'convert_rate',
    ];

    //J'établis une relation entre la table convert_table et son parent currencies

    public function fromCurrency(){
        return $this->belongsTo(Currencies::class, 'from_currency_id');
    }

    public function toCurrency(){
        return $this->belongsTo(Currencies::class, 'to_currency_id');
    }

    //relation  entre la table convert_tables et la table requests
    public function requests()
    {
        return $this->hasMany(Requests::class, 'pair_id');
    }
}
