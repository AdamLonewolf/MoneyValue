<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyRessource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyController extends Controller
{
    /**
     * Retourne la liste des monnaies
     */
    public function index()
    {
          
        try{
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des devises',
                'data' => CurrencyRessource::collection(Currency::all()) //On chaque objet de la collection en un tableau JSON
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }


    /**
     * Permettra d'ajouter une nouvelle devise
     */
    public function store(Request $request)
    {
        try{
            //On vérifie les informations avant de créer une nouvelle devise
         Validator::extend('unique_currency', function(Request $request)
         {
             //On récupère les informations dans le formulaire (nom et code de la devise)
             $CurrencyCode = $request->input('currency_code');
             $CurrencyName = $request->input('currency_name');
 
             //On vérifie à présent si la devise existe déjà dans la table.
 
             $exists = Currency::where('currency_code', $CurrencyCode) 
                                         ->where('currency_name', $CurrencyName)
                                         ->exists(); //retourne true si on trouve une devise
             return !$exists; //Si exists() retourne true, !$existe retourne false ce qui bloquera la validation des données.
         });
       
         
         $newCurrency = Currency::create([
             'currency_code' => $request->currency_code, //Récupère le code de la devise
             'currency_name' => $request->currency_name, //Récupère le nom de la devise
         ]);
         
         //Si la création de la devise a été effectuée, alors on envoie une réponse de succès 

          if($newCurrency){
         return response()->json(
             [
                 'status' => "OK",
                 'message' => "Votre devise a été enregistrée avec succès"
             ]
         );
    }
        } catch(Exception $e) {
            return response()->json($e);
        }

        
    }

    /**
     * Mettra à jour une nouvelle devise
     */
    public function update(Request $request, string $id)
    {
        try{
            //On vérifie les informations avant de créer une nouvelle devise
         Validator::extend('unique_currency', function(Request $request)
         {
             //On récupère les informations dans le formulaire (nom et code de la devise)
             $CurrencyCode = $request->input('currency_code');
             $CurrencyName = $request->input('currency_name');
 
             //On vérifie à présent si la devise existe déjà dans la table.
 
             $exists = Currency::where('currency_code', $CurrencyCode) 
                                         ->where('currency_name', $CurrencyName)
                                         ->exists(); //retourne true si on trouve une devise
             return !$exists; //Si exists() retourne true, !$existe retourne false ce qui bloquera la validation des données.
         });
       
         $editCurrency = Currency::findOrfail($id); //On récupère l'id de la devise et on la cherche dans la table currency

         //On met à jour les informations de la devise selectionnée

         $editCurrency->update([
             'currency_code' => $request->currency_code, //Récupère le code de la devise
             'currency_name' => $request->currency_name, //Récupère le nom de la devise
         ]);
         
         //Si la création de la devise a été effectuée, alors on envoie une réponse de succès 

          if($editCurrency){
         return response()->json(
             [
                 'status' => "OK",
                 'message' => "Votre devise a été modifiée avec succès"
             ]
         );
    }
        } catch(Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Sert à supprimer une devise
     */
    public function destroy($id)
    {
        try{
            $destroyCurrency = Currency::findOrfail($id);
            $destroyCurrency->delete(); //On supprime la paire séléctionnée
            return response()->json([
                'status' => "OK",
                'message' => 'La devise a été supprimée',
            ]);
        } catch (ModelNotFoundException $e){
            return response()->json($e); //Je retourne une erreur si la paire n'a pas été trouvée
        }
    }
}
