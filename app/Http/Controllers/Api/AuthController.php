<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Fonction qui servira à vérifier les données de l'utilisateur (admin) lorsqu'il se connecte
     */

    public function login(Request $request){

        //On vérifie si l'utilisateur rentre bien ses informations

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ], 
        [
            "email.required" => "Votre email est requis",
            "email.email" => "Votre email n'est pas valide",
            "password.required" => "Votre mot de passe est requis"
        ]);

        //Ici on va chercher à vérifier si les informations de l'utilisateur sont conformes aux infos de la table users

        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials)){
            $user = Auth::user();

            //Je fais un token pour l'administrateur 
            //Si $user est une instance du modèle user
            if ($user instanceof \App\Models\User) {
                $token = $user->createToken('my_admin_token')->plainTextToken;
                // Autres actions à effectuer avec le token
            } 

            if($user->role_id == 1){
                //Si le role_id est égal à 1 alors on confirme qu'il s'agit de l'admin
                return response()->json([
                    "status"=>'OK',
                    "message"=>"Vous êtes connecté en tant qu'administrateur.",
                    "user"=>$user,
                    "token"=>$token
                ]);
            }

        } else {
            //Si les données sont erronées alors (Les infos saisies ne correspondent à aucun élément de la table users).
            return response()->json([
                "status"=>'Error',
                "message"=>"Vos informations sont incorrectes."
            ]);
        }
    }
}
