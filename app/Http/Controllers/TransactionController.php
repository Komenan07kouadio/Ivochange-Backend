<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Exception;

class TransactionController extends Controller
{
    /**
     * Constructeur du contrôleur pour vérifier l'authentification
     */
    public function __construct()
    {
        $this->middleware('auth'); // Vérifie l'authentification pour toutes les méthodes du contrôleur
    }

    /**
     * Afficher toutes les transactions de l'utilisateur actuel
     */
    public function index()
    {
        try {
            $transactions = Transactions::where('utilisateur_id', session('utilisateur'))->get();
            return response()->json(['success' => true, 'transactions' => $transactions]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [         
                'utilisateur_id' => 'required|exists:utilisateurs,id',
                'montant_envoye' => 'required|numeric',
                'numero_compte_envoye' => 'required|numeric',
                'montant_reçu' => 'required|numeric',
                'numero_compte_reçu' => 'required|numeric',
                'devise_id' => 'required|exists:devises,id',
                'montant_frais_inclus_envoye' => 'required|numeric',
                'montant_frais_inclus_reçu' => 'required|numeric',
                'statut' => 'required|string',
            ],
            [
                'utilisateur_id.required' => 'Le champ id_utilisateur est requis.',
                'utilisateur_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',

                'montant_envoye.required' => 'Le champ montant envoyé est requis.',
                'montant_envoye.numeric' => 'Le champ montant envoyé doit être numérique.',

                'numero_compte_envoye.required' => 'Le champ numéro de compte envoyé est requis.',
                'numero_compte_envoye.numeric' => 'Le numéro de compte envoyé doit être numérique.',

                'montant_reçu.required' => 'Le champ montant reçu est requis.',
                'montant_reçu.numeric' => 'Le champ montant reçu doit être numérique.',

                'numero_compte_reçu.required' => 'Le champ numéro de compte reçu est requis.',
                'numero_compte_reçu.numeric' => 'Le numéro de compte reçu doit être numérique.',

                'devise_id.required' => 'Le champ devise est requis.',
                'devise_id.exists' => 'La devise sélectionnée n\'existe pas.',

                'montant_frais_inclus_envoye.required' => 'Le champ montant avec frais inclus envoyé est requis.',
                'montant_frais_inclus_envoye.numeric' => 'Le montant avec frais inclus envoyé doit être numérique.',

                'montant_frais_inclus_reçu.required' => 'Le champ montant avec frais inclus reçu est requis.',
                'montant_frais_inclus_reçu.numeric' => 'Le montant avec frais inclus reçu doit être numérique.',
                        
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            $transactionData = $request->all();
            $transactionData['id_utilisateur'] = session('utilisateurs');
    
            $transaction = Transactions::create($transactionData);
    
            return response()->json(['success' => true, 'transactions' => $transaction], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Mettre à jour une transaction de l'utilisateur actuel
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'utilisateur_id' => 'sometimes|required|exists:utilisateurs,id',
                'montant_envoye' => 'sometimes|required|numeric',
                'numero_compte_envoye' => 'sometimes|required|numeric',
                'montant_reçu' => 'sometimes|required|numeric',
                'numero_compte_reçu' => 'sometimes|required|numeric',
                'devise_id' => 'sometimes|required|exists:devises,id',
                'montant_frais_inclus_envoye' => 'sometimes|required|numeric',
                'montant_frais_inclus_reçu' => 'sometimes|required|numeric',
                'statut' => 'sometimes|required|string',
            ],
            [
                'utilisateur_id.required' => 'Le champ id_utilisateur est requis.',
                'utilisateur_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',

                'montant_envoye.required' => 'Le champ montant envoyé est requis.',
                'montant_envoye.numeric' => 'Le champ montant envoyé doit être numérique.',

                'numero_compte_envoye.required' => 'Le champ numéro de compte envoyé est requis.',
                'numero_compte_envoye.numeric' => 'Le numéro de compte envoyé doit être numérique.',

                'montant_reçu.required' => 'Le champ montant reçu est requis.',
                'montant_reçu.numeric' => 'Le champ montant reçu doit être numérique.',

                'numero_compte_reçu.required' => 'Le champ numéro de compte reçu est requis.',
                'numero_compte_reçu.numeric' => 'Le numéro de compte reçu doit être numérique.',

                'devise_id.required' => 'Le champ devise est requis.',
                'devise_id.exists' => 'La devise sélectionnée n\'existe pas.',

                'montant_frais_inclus_envoye.required' => 'Le champ montant avec frais inclus envoyé est requis.',
                'montant_frais_inclus_envoye.numeric' => 'Le montant avec frais inclus envoyé doit être numérique.',

                'montant_frais_inclus_reçu.required' => 'Le champ montant avec frais inclus reçu est requis.',
                'montant_frais_inclus_reçu.numeric' => 'Le montant avec frais inclus reçu doit être numérique.',
            ],
        );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $transaction = Transactions::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $transaction->update($request->all());

            return response()->json(['success' => true, 'transactions' => $transaction]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer une transaction de l'utilisateur actuel
     */
    public function destroy($id)
    {
        try {
            $transaction = Transactions::where('utilisateur_id', session('utilisateurs'))->findOrFail($id);
            $transaction->delete();

            return response()->json(['success' => true, 'message' => 'Transaction supprimée avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

