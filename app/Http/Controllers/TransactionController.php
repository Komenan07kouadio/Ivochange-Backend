<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
            $transactions = Transaction::where('utilisateur_id', session('utilisateur'))->get();
            return response()->json(['success' => true, 'transactions' => $transactions]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Afficher une transaction spécifique de l'utilisateur actuel
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            return response()->json(['success' => true, 'transaction' => $transaction]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Créer une nouvelle transaction pour l'utilisateur actuel
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'portefeuille_id' => 'required|exists:portefeuilles,id',
                'montant_envoye' => 'required|numeric',
                'montant' => 'required|numeric',
                'montant_reçu' => 'required|numeric',
                'devise_id' => 'required|exists:devises,id',
                'type' => 'required|string',
            ],
            [
                'portefeuille_id.required' => 'Le champ portefeuille_id est requis.',
                'portefeuille_id.exists' => 'Le portefeuille sélectionné n\'existe pas.',
                'montant_envoye.required' => 'Le champ montant_envoye est requis.',
                'montant_envoye.numeric' => 'Le champ montant_envoye doit être numérique.',
                'montant.required' => 'Le champ montant est requis.',
                'montant.numeric' => 'Le champ montant doit être numérique.',
                'montant_reçu.required' => 'Le champ montant_reçu est requis.',
                'montant_reçu.numeric' => 'Le champ montant_reçu doit être numérique.',
                'devise_id.required' => 'Le champ devise_id est requis.',
                'devise_id.exists' => 'La devise sélectionnée n\'existe pas.',
                'type.required' => 'Le champ type est requis.',
                'type.string' => 'Le champ type doit être une chaîne de caractères.',
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            $transactionData = $request->all();
            $transactionData['utilisateur_id'] = session('utilisateur');
    
            $transaction = Transaction::create($transactionData);
    
            return response()->json(['success' => true, 'transaction' => $transaction], 201);
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
            'portefeuille_id' => 'sometimes|required|exists:portefeuilles,id',
            'montant_envoye' => 'sometimes|required|numeric',
            'montant' => 'sometimes|required|numeric',
            'montant_reçu' => 'sometimes|required|numeric',
            'devise_id' => 'sometimes|required|exists:devises,id',
            'type' => 'sometimes|required|string',
        ],
        [
            'portefeuille_id.required' => 'Le champ portefeuille_id est requis.',
            'portefeuille_id.exists' => 'Le portefeuille sélectionné n\'existe pas.',
            'montant_envoye.required' => 'Le champ montant_envoye est requis.',
            'montant_envoye.numeric' => 'Le champ montant_envoye doit être numérique.',
            'montant.required' => 'Le champ montant est requis.',
            'montant.numeric' => 'Le champ montant doit être numérique.',
            'montant_reçu.required' => 'Le champ montant_reçu est requis.',
            'montant_reçu.numeric' => 'Le champ montant_reçu doit être numérique.',
            'devise_id.required' => 'Le champ devise_id est requis.',
            'devise_id.exists' => 'La devise sélectionnée n\'existe pas.',
            'type.required' => 'Le champ type est requis.',
            'type.string' => 'Le champ type doit être une chaîne de caractères.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $transaction = Transaction::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
        $transaction->update($request->all());

        return response()->json(['success' => true, 'transaction' => $transaction]);
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
            $transaction = Transaction::where('utilisateur_id', session('utilisateur'))->findOrFail($id);
            $transaction->delete();

            return response()->json(['success' => true, 'message' => 'Transaction supprimée avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

