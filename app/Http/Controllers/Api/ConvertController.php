<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Requests;
use Illuminate\Http\Request;
use App\Models\Convert_table;
use App\Http\Controllers\Controller;
use App\Http\Resources\PairRessource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConvertController extends Controller
{



    /**
     * retourne la liste des paires
     */

     public function index(){
        try{
    
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des paires',
                'data' => PairRessource::collection(Convert_table::all()),//On récupère la liste paginée des élements de notre tableau.

            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
     }


    /**
     * Retourne la liste des paires paginées
     */
    public function paginatePair($page)
    {
        try {
            $perPage = 10; // Nombre d'éléments par page
    
            // Compter le nombre total de pages
            $totalCount = Convert_table::count(); //on compte tous les enregistrements de la table Convert
            $totalPage = ceil($totalCount / $perPage);
    
            // Calculer l'offset
            $offset = ($page - 1) * $perPage;
    
            // Récupérer les éléments paginés de la table
            $pairs = Convert_table::skip($offset)->take($perPage)->get();
    
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des paires',
                'data' => PairRessource::collection($pairs),
                'totalPage' => $totalPage
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
//On récupère les informations du formulaire passées par la requête.
$validatedData = $request->validate([
    'from_currency_id' => 'required',
    'to_currency_id' => 'required',
    'convert_rate' => 'required',
]);

try {
        //On cherche dans la table si une paire identique existe déjà
        $exists = Convert_table::where('from_currency_id', $validatedData['from_currency_id'])
            ->where('to_currency_id', $validatedData['to_currency_id'])
            ->exists();
        
            //si oui, on retourne une erreur
        if ($exists) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Une paire avec les mêmes données existe déjà'
            ]);
        }
        
        //Si non, On crée une nouvelle paire

        $newPair = Convert_table::create([
            'from_currency_id' => $validatedData['from_currency_id'],
            'to_currency_id' => $validatedData['to_currency_id'],
            'convert_rate' => $validatedData['convert_rate'],
        ]);

        if ($newPair) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Votre devise a été enregistrée avec succès'
            ]);
        }
    } catch (Exception $e) {
        return response()->json($e);
    }

    }


    /**
     * Permet de modifier et mettre à jour les données d'une paire sélectionnée.
     */

    public function update(Request $request, $id)
    {
        //On récupère les informations du formulaire passées par la requête.
        $validatedData = $request->validate([
            'from_currency_id' => 'required',
            'to_currency_id' => 'required',
            'convert_rate' => 'required',
        ]);

        try {
            //On cherche dans la table si une paire identique existe déjà
            $exists = Convert_table::where('from_currency_id', $validatedData['from_currency_id'])
                ->where('to_currency_id', $validatedData['to_currency_id'])
                ->where('convert_rate', $validatedData['convert_rate'])
                ->exists();
            
                //si oui, on retourne une erreur
            if ($exists) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Une paire avec les mêmes données existe déjà'
                ]);
            }
            
            //Si non, On récupère l'id de cette paire et on la met à jour.
            $editPair = Convert_table::findOrfail($id);
            $editPair->update([
                'from_currency_id' => $validatedData['from_currency_id'],
                'to_currency_id' => $validatedData['to_currency_id'],
                'convert_rate' => $validatedData['convert_rate'],
            ]);
    
            if ($editPair) {
                return response()->json([
                    'status' => 'OK',
                    'message' => 'Votre devise a été enregistrée avec succès'
                ]);
            }
        } catch (Exception $e) {
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
