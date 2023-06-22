<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Requests;
use Illuminate\Http\Request;
use App\Models\Convert_table;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConvertController extends Controller
{
    /**
     * Retourne la liste des paires supportées
     */
    public function index()
    {
        //Quand la requête est lancée, on essaie de retourner une réponse en json contenant la liste des paires si c'est un succès, sinon on retourne une erreur 
        
        try{
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des posts',
                'data' => Convert_table::all()
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }


    /**
     * Fonction qui va me servir à la conversion d'une paire de monnaie
     */

     public function convert(Request $request, $id){

        try{
           $convert =  Convert_table::findOrfail($id); //on cherche l'id de la paire voulue.
           $convert_rate = $convert->convert_rate; //on récupère le taux de change de la paire //On récupère la valeur du compteur 

           //On va effectuer un calcul pour trouver le montant qu'aura la monnaie cible 
           //Montant en devise de destination = Montant en devise d'origine x Taux de change

           $quantity = $request->input('quantity');
           $amount = $quantity * $convert_rate;
           $convert->save(); //On enregistre les modifications
           
           //On crée une requête pour la paire de conversion sélectionnée

            $pairRequest = Requests::where('pair_id', $id)->first(); //on recherche une requête existante pour la paire 
            if($pairRequest){
                //si elle existe, on incrémente le compteur de la requête existante
                $pairRequest->request_count++; 
                $pairRequest->save();
            } else {
                //si elle n'existe pas, on en crée une
                $pairRequest = new Requests();
                $pairRequest->pair_id = $id; //On lie l'id de la paire actuelle à la clé étrangère
                $pairRequest->request_count++; //on incrémente le nombre de requêtes de la paire
                $pairRequest->save();
            }

           

           //On retourne le montant sous format json 
           return response()->json([
            'status' => "OK",
            'message' => 'Merci d\'avoir utilisé notre service !',
            'data' => $amount
        ]);

        } catch(Exception $e) {
            return response()->json($e);
        }

     }


    /**
     * Va stocker une nouvelle paire de monnaie crée, dans la base de données
     */
    public function store(Request $request)
    {

        try{
            //On vérifie les informations avant de créer notre paire
         Validator::extend('unique_currency_pair', function(Request $request, $parameters)
         {
             //On récupère les valeurs des id de chaque monnaie de la paire.
             $fromCurrencyId = $request->input($parameters[0]);
             $toCurrencyId = $request->input($parameters[1]);
 
             //On vérifie à présent si la combinaison de ces deux paires existe déjà dans la table.
 
             $exists = Convert_table::where('from_currency_id', $fromCurrencyId) 
                                         ->where('to_currency_id', $toCurrencyId)
                                         ->exists(); //retourne true si une combinaison est trouvée, et false si non.
             return !$exists; //Si exists() retourne true, !$existe retourne false ce qui bloquera la validation des données.
             
         });
       
         
         $newPair = Convert_table::create([
             'from_currency_id' => $request->from_currency_id, //Récupère l'id de la monnaie source
             'to_currency_id' => $request->to_currency_id, //Récupère l'id de la monnaie cible
             'convert_rate' => $request->convert_rate, // Récupère la valeur du taux de change
             'request_count' => 0, // Compteur initialisé à 0
         ]);
         
         //Si la création de la paire a été effectuée, alors on envoie une réponse de succès 

          if($newPair){
         return response()->json(
             [
                 'status' => "OK",
                 'message' => "Votre paire a été enregistrée avec"
             ]
         );
    }
        } catch(Exception $e) {
            return response()->json($e);
        }

        



    }


    /**
     * Permet de modifier et mettre à jour les données d'une paire sélectionnée.
     */

    public function update(Request $request, $id)
    {
        try{

            //On vérifie les informations avant de créer notre paire
         Validator::extend('unique_currency_pair', function(Request $request, $parameters)
         {
             //On récupère les valeurs des id de chaque monnaie de la paire.
             $fromCurrencyId = $request->input($parameters[0]);
             $toCurrencyId = $request->input($parameters[1]);
 
             //On vérifie à présent si la combinaison de ces deux paires existe déjà dans la table.
 
             $exists = Convert_table::where('from_currency_id', $fromCurrencyId) 
                                         ->where('to_currency_id', $toCurrencyId)
                                         ->exists(); //retourne true si une combinaison est trouvée, et false si non.
             return !$exists; //Si exists() retourne true, !$existe retourne false ce qui bloquera la validation des données.
             
         });
       
         $editPair = Convert_table::findOrfail($id); //On recherche l'id de la paire selectionnée
         $editPair->update([
             'from_currency_id' => $request->from_currency_id, //Récupère l'id de la monnaie source
             'to_currency_id' => $request->to_currency_id, //Récupère l'id de la monnaie cible
             'convert_rate' => $request->convert_rate, // Récupère la valeur du taux de change
         ]);
 
         //Si la mise à jour des données a été effectuée, alors on envoie une réponse de succès

          if($editPair){
         return response()->json(
             [
                 'status' => "OK",
                 'message' => "Votre paire a été modifiée avec succès"
             ]
         );
    }
        } catch(Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Supprime la paire séléctionné de la table.
     */
    
    public function destroy(Request $request, $id)
    {
        try{
            $destroyPair = Convert_table::findOrfail($id);
            $destroyPair->delete(); //On supprime la paire séléctionnée
            return response()->json([
                'status' => "OK",
                'message' => 'La paire a été supprimée',
                'data' => []
            ]);
        } catch (ModelNotFoundException $e){
            return response()->json($e); //Je retourne une erreur si la paire n'a pas été trouvée
        }
    }
}
