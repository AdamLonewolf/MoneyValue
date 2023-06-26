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


    public function index(){
        try{
            return response()->json([
                'status' => "OK",
                'message' => 'Liste des devises',
                'data' => CurrencyRessource::collection(Currency::all()),
                 //On chaque objet de la collection en un tableau JSON
    
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Retourne la liste des devises paginées
     */
    public function PaginateCurrency($page)
    {   
        try {
        $perPage = 10; // Nombre d'éléments par page
    
            // Compter le nombre total de pages
            $totalCount = Currency::count(); //on compte tous les enregistrements de la table Currency
            $totalPage = ceil($totalCount / $perPage);

            // Calculer l'offset
            $offset = ($page - 1) * $perPage;

            // Récupérer les éléments paginés de la table
            $currency = Currency::skip($offset)->take($perPage)->get();

            return response()->json([
                'status' => "OK",
                'message' => 'Liste des devises',
                'data' => CurrencyRessource::collection($currency),
                'totalPage' => $totalPage
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
        $validatedData = $request->validate([
            'currency_code' => 'required',
            'currency_name' => 'required'
        ]);

        try {
            $exists = Currency::where('currency_code', $validatedData['currency_code'])
                ->where('currency_name', $validatedData['currency_name'])
                ->exists();
    
            if ($exists) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Une devise avec le même code et le même nom existe déjà'
                ]);
            }
    
            $newCurrency = Currency::create([
                'currency_code' => $validatedData['currency_code'],
                'currency_name' => $validatedData['currency_name'],
            ]);
    
            if ($newCurrency) {
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
     * Mettra à jour une nouvelle devise
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'currency_code' => 'required',
            'currency_name' => 'required'
        ]);

        try {
            $exists = Currency::where('currency_code', $validatedData['currency_code'])
                ->where('currency_name', $validatedData['currency_name'])
                ->exists();
    
            if ($exists) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Une devise avec le même code et le même nom existe déjà'
                ]);
            }
            
            $editCurrency = Currency::findOrfail($id);
            $editCurrency->update([
                'currency_code' => $validatedData['currency_code'],
                'currency_name' => $validatedData['currency_name'],
            ]);
    
            if ($editCurrency) {
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
