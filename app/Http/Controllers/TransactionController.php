<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Afficher toutes les transactions
     */
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json($transactions);
    }

    /**
     * Créer une nouvelle transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'portefeuille_id' => 'required|exists:portefeuilles,id',
            'montant_envoye' => 'nullable|numeric',
            'montant_reçu' => 'nullable|numeric',
            'montant' => 'required|numeric',
            'devise_id' => 'required|exists:devises,id',
            'type' => 'required|in:achat,vente',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transaction = Transaction::create($request->all());

        return response()->json($transaction, 201);
    }

    /**
     * Afficher une transaction spécifique
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json($transaction);
    }

    /**
     * Mettre à jour une transaction
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'utilisateur_id' => 'sometimes|required|exists:utilisateurs,id',
            'portefeuille_id' => 'sometimes|required|exists:portefeuilles,id',
            'montant_envoye' => 'nullable|numeric',
            'montant_reçu' => 'nullable|numeric',
            'montant' => 'sometimes|required|numeric',
            'devise_id' => 'sometimes|required|exists:devises,id',
            'type' => 'sometimes|required|in:achat,vente',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        return response()->json($transaction);
    }

    /**
     * Supprimer une transaction
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction supprimée avec succès'], 204);
    }
}
