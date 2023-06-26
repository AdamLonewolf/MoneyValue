<?php

use Illuminate\Http\Request;
use App\Models\Convert_table;
use App\Http\Resources\PairRessource;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\isEmpty;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConvertController;
use App\Http\Controllers\Api\CurrencyController;




//Vérification du serveur de l'api (voir s'il est fonctionnel)

Route::get('/test', function(){
    $convert = Convert_table::all(); //je récupère toute la collection de ma table de conversion.
    
    //Si ma collection convert_table n'est pas vide, alors on a un message de succès, dans le cas contraire, on a un message d'erreur

    if (!$convert->isEmpty()) {
        return response()->json([
            "status" => "OK",
            "message" => "Le service est fonctionnel.",
            "data" => [],
        ]);
    } else {
        return response()->json([
            "status" => "Error",
            "message" => "Le service rencontre un problème.",
            "data" => [],
        ]);
    }
});

//----Partie publique (paires)---------

//Route pour récupérer la liste des paires de devises

Route::get('/pairs/list', [ConvertController::class, 'index']);

//Route pour récupérer les informations d'une paire de devises

Route::get('/pair/{id}', function($id){
    $pair = Convert_table::find($id); //On cherche la paire à partir de l'id 
    //Si elle n'est pas trouvée, on retourne une erreur 
    if (!$pair) {
        return response()->json([
            'status' => 'Error',
            'message' => 'Paire non trouvée',
        ]);
    }
    //si elle est trouvée, on renvoie ses données
    return new PairRessource($pair); 
});

//Route qui permettra de convertir une quantité de devise suivant une paire existante

Route::post('/convert/{id}', [ConvertController::class, 'convert']);



//---Partie publique (Devises)---

//Route pour récupérer la liste des devises 

Route::get('/currencies/list', [CurrencyController::class, 'index']);





//----Routes définies pour l'administrateur (privées)---


//---Partie Privée(CRUD paires)---



    //Route pour ajouter une nouvelle paire de conversion 

Route::post('/pairs/create', [ConvertController::class, 'store']);

    //Route pour modifier une paire de convertion 

Route::post('/pairs/edit/{id}', [ConvertController::class, 'update']);

    //Route pour supprimer une paire de conversion 

Route::delete('/pairs/delete/{id}', [ConvertController::class, 'destroy']);



//---Partie Privée(CRUD devises)---



//Route pour créer une nouvelle devise
Route::post('/currency/create', [CurrencyController::class, 'store']);

//Route pour modifier une devise
Route::post('/currency/edit/{id}', [CurrencyController::class, 'update']);

//Route pour supprimer une devise
Route::delete('/currency/delete/{id}', [CurrencyController::class, 'destroy']);



//---Authentification----

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    //Connexion de l'admin

    Route::post('/users/login', [AuthController::class, 'login']);

    //Déconnexion de l'admin 

    Route::get('/users/logout/{id}', [AuthController::class, 'logout']);