<?php

namespace App\Http\Resources;

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
        //  return parent::toArray($request);
        return[
            'id'=>$this->id,
            'from_currency_id'=>$this->from_currency_id,
            'from_currency_code'=>$this->fromCurrency->currency_code,
            'to_currency_id'=>$this->to_currency_id,
            'to_currency_code'=>$this->toCurrency->currency_code,
            'convert_rate'=>$this->convert_rate
        ];
    }
}
