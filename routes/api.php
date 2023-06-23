<?php

use Illuminate\Http\Request;
use App\Models\Convert_table;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\isEmpty;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConvertController;

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

//Route pour récupérer la liste des paires de conversion 

Route::get('/pairs/list', [ConvertController::class, 'index']);

//Route qui permettra de convertir une quantité de devise suivant une paire existante

Route::post('/convert/{id}', [ConvertController::class, 'convert']);

//CRUD pour l'administrateur

    //Route pour ajouter une nouvelle paire de conversion 

Route::post('/pairs/create', [ConvertController::class, 'create']);

    //Route pour modifier une paire de convertion 

Route::delete('/pairs/edit/{id}', [ConvertController::class, 'update']);

    //Route pour supprimer une paire de conversion 

Route::delete('/pairs/delete/{id}', [ConvertController::class, 'destroy']);


//---Authentification--z--

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    //Connexion de l'admin

    Route::post('/users/login', [AuthController::class, 'login']);
