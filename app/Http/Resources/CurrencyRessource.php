<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyRessource extends JsonResource
{
    /**
     * Transforme mes données stockées dans la liste de devises en format Json.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'currency_code'=>$this->currency_code,
            'currency_name'=>$this->currency_name,
        ];
    }
}
