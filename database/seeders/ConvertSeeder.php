<?php

namespace Database\Seeders;

use App\Models\Convert_table;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConvertSeeder extends Seeder
{
    /**
     * seeder qui servira à créer mes paires de monnaie
     */
    public function run(): void
    {
        
        $currency = Currency::all(); //je récupère toutes mes monnaies stockées dans le tableau currencies.

        //Je crée des paires avec la méthode crossJoin() de Laravel

        $pairs = $currency->crossJoin($currency)->reject(function($pair){
            return $pair[0]->id === $pair[1]->id; //On rejette dans cette méthode toutes les monnaies qui forment des paires avec elles-mêmes (auto-paire)
        }); 

        
        

        foreach($pairs as $p){
            $convert = new Convert_table();
            $convert_rate = round(mt_rand(1, 160) / 100, 2); //Taux de conversion arrondi à 2 chiffres après la virgule
            $request_nbr = 0; //nombre de requêtes par défaut

            $convert->from_currency_id = $p[0]->id; 
            $convert->to_currency_id = $p[1]->id;
            $convert->convert_rate = $convert_rate;
            $convert->request_count = $request_nbr;
            $convert->save();

        }


    }
}
