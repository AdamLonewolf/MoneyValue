<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convert_table extends Model
{
    use HasFactory;

    
    protected $table = "convert_tables";
    protected $primaryKey = "id";
    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'convert_rate',
        'request_count',
    ];

    //J'établis une relation entre la table convert_table et son parent currencies

    //Relation qui va nous permettre de récupérer la monnaie source (celle qu'on doit convertir)
    public function fromCurrency(){
        return $this->belongsTo(Currencies::class, 'from_currency_id');
    }

    //Relation qui va nous permettre de récupérer la monnaie cible (celle en quoi la monnaie source doit être convertie)

    public function toCurrency(){
        return $this->belongsTo(Currencies::class, 'to_currency_id');
    }
}
