<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyRessource;

class CurrencyController extends Controller
{
    public function index(){
         
        try{
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des posts',
                'data' => CurrencyRessource::collection(Currency::all()) //On chaque objet de la collection en un tableau JSON
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }


    }
}
