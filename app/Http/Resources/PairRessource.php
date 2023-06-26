<?php

namespace App\Http\Resources;

use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PairRessource extends JsonResource
{
    /**
     * Transforme le modèle Pairs et ses données en une représentation json
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   

        $requestCount = 0; // Valeur par défaut de request_count

        $requests = Requests::where('pair_id', $this->id)->get();  //On recupère les enregistrement de la table requests qui correspondent à la paire courante 
    
        if ($requests->isNotEmpty()) {
            $request = $requests->first(); // Prend le premier enregistrement de la collection
            $requestCount = $request->request_count; // Assigner la valeur de request_count pour la paire
        } else {
            $requestCount = 0;
        }
        
        //  return parent::toArray($request);
        return[
            'id'=>$this->id,
            'from_currency_id'=>$this->from_currency_id,
            'from_currency_code'=>$this->fromCurrency->currency_code,
            'to_currency_id'=>$this->to_currency_id,
            'to_currency_code'=>$this->toCurrency->currency_code,
            'convert_rate'=>$this->convert_rate,
            'requests' => $requestCount
        ];
    }
}
