<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     *Je crée des monnaies que je vais seed dans ma base de données
     */
    public function run(): void
    {
        $currency = new Currency();
        $currency->currency_code = "USD";
        $currency->currency_name = "Dollar américain";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "EUR";
        $currency->currency_name = "Euro";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "GBP";
        $currency->currency_name = "Livre sterling";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "JPY";
        $currency->currency_name = "Yen japonais";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "CAD";
        $currency->currency_name = "Dollar canadien";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "AUD";
        $currency->currency_name = "Dollar australien";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "CHF";
        $currency->currency_name = "Franc suisse";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "CNY";
        $currency->currency_name = "Yuan chinois";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "INR";
        $currency->currency_name = "Roupie indienne";
        $currency->save();

        $currency = new Currency();
        $currency->currency_code = "MXN";
        $currency->currency_name = "Peso mexicain";
        $currency->save();
    }
}
