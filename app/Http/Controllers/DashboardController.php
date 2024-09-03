<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur; // Correction: Nom de modèle en majuscule
use App\Models\Bien;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Ajout de l'importation d'Auth pour la récupération de l'utilisateur connecté


class DashboardDataController extends Controller
{
    /**
     * Afficher le tableau de bord du superAdmin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Logique pour récupérer les données du dashboard superAdmin
            $data = [
                'totalUsers' => \App\Models\User::count(),
                'totalRoles' => \App\Models\Role::count(),
                'recentActivities' => \App\Models\ActivityLog::orderBy('created_at', 'desc')->take(10)->get(),
                // Ajoutez d'autres statistiques ou données pertinentes
            ];

            // Retourner la vue avec les données du dashboard
            return view('superAdmin.dashboard', compact('data'));

        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs
            Log::error('Erreur lors du chargement du dashboard SuperAdmin: ' . $e->getMessage());

            // Rediriger vers une page d'erreur ou afficher un message d'erreur
            return redirect()->route('error.page')->with('error', 'Une erreur est survenue lors du chargement du tableau de bord.');
        }
    }

    public function getDashboardData($proprietaireId)
    {
        // Récupérer les informations du propriétaire
        $proprietaire = Utilisateur::find($proprietaireId); // Correction: Nom de modèle en majuscule
        if (!$proprietaire) {
            return response()->json(['error' => 'Propriétaire non trouvé'], 404);
        }

        // Récupérer les biens du propriétaire
        $biens = $proprietaire->biens; // Correction: Appel à la relation Eloquent sans parenthèses
        $nbrOfBiens = $biens->count();
        
        $propRecoredRglmt = $proprietaire->reglements; // Correction: Appel à la relation Eloquent sans parenthèses
        $propNbreRglmt = $propRecoredRglmt->count();

        $allLocataire = $proprietaire->locataires; // Correction: Appel à la relation Eloquent sans parenthèses
        $nbreOfLocataire = $allLocataire->count();

        // Compiler les données du tableau de bord
        $dashboardData = [
            'proprietaire' => $proprietaire,
            'biens' => $biens,
            'nbreDeBiens' => $nbrOfBiens,
            'reglementsDestinesProprio' => $propRecoredRglmt,
            'nbrReglement' => $propNbreRglmt,
            'locataires' => $allLocataire,
            'nbreLocataires' => $nbreOfLocataire,
        ];

        // Retourner les données sous forme de JSON
        return response()->json($dashboardData);
    }

    /**
     * Afficher le tableau de bord en fonction du rôle de l'utilisateur.
     *
     * @return \Illuminate\Http\Response
     */
    public function Dashboard()
    {
        try {
            // Récupérer l'utilisateur connecté
            $user = Auth::user();

            // Vérifier le rôle de l'utilisateur et récupérer les données du tableau de bord
            if ($user->role == 'superAdmin') {
                $data = $this->getSuperAdminData();
                return view('superAdmin.dashboard', compact('data'));
            } elseif ($user->role == 'admin') {
                $data = $this->getAdminData();
                return view('admin.dashboard', compact('data'));
            } elseif ($user->role == 'utilisateur') {
                $data = $this->getUtilisateurData();
                return view('utilisateur.dashboard', compact('data'));
            } else {
                // Par défaut, si le rôle n'est pas reconnu
                return view('default.dashboard');
            }

        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs
            Log::error('Erreur lors du chargement du dashboard: ' . $e->getMessage());

            // Rediriger vers une page d'erreur ou afficher un message d'erreur
            return redirect()->route('error.page')->with('error', 'Une erreur est survenue lors du chargement du tableau de bord.');
        }
    }

    /**
     * Récupérer les données du tableau de bord pour le superAdmin.
     *
     * @return array
     */
    protected function getSuperAdminData()
    {
        return [
            'totalUsers' => \App\Models\User::count(),
            'totalRoles' => \App\Models\Role::count(),
            'recentActivities' => \App\Models\ActivityLog::orderBy('created_at', 'desc')->take(10)->get(),
            // Ajoutez d'autres statistiques ou données pertinentes
        ];
    }

    /**
     * Récupérer les données du tableau de bord pour l'admin.
     *
     * @return array
     */
    protected function getAdminData()
    {
        return [
            'totalUsers' => \App\Models\User::count(),
            'totalTransactions' => \App\Models\Transaction::count(),
            // Ajoutez d'autres statistiques ou données pertinentes pour l'admin
        ];
    }

    /**
     * Récupérer les données du tableau de bord pour l'utilisateur.
     *
     * @return array
     */
    protected function getUtilisateurData()
    {
        return [
            'myTransactions' => \App\Models\Transaction::where('user_id', Auth::id())->count(),
            // Ajoutez d'autres statistiques ou données pertinentes pour l'utilisateur
        ];
    }
}
