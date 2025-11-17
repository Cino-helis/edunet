<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Note;
use App\Models\Resultat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistiques générales
        $stats = $this->getGeneralStats();
        
        // 2. Performance par filière
        $performanceParFiliere = $this->getPerformanceParFiliere();
        
        // 3. Activités récentes
        $activitesRecentes = $this->getActivitesRecentes();
        
        return view('admin.dashboard', compact(
            'stats',
            'performanceParFiliere',
            'activitesRecentes'
        ));
    }
    
    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats()
    {
        // Nombre total d'étudiants
        $totalEtudiants = Etudiant::count();
        
        // Variation du mois dernier
        $etudiantsMoisDernier = Etudiant::whereMonth('created_at', Carbon::now()->subMonth()->month)
                                        ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                        ->count();
        
        $variationEtudiants = $etudiantsMoisDernier > 0 
            ? round((($totalEtudiants - $etudiantsMoisDernier) / $etudiantsMoisDernier) * 100, 1)
            : 0;
        
        // Nombre total d'enseignants
        $totalEnseignants = Enseignant::count();
        
        // Nouveaux enseignants ce mois
        $nouveauxEnseignants = Enseignant::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // Moyenne générale
        $moyenneGenerale = Note::avg('valeur') ?? 0;
        $moyenneGenerale = round($moyenneGenerale, 1);
        
        // Moyenne du semestre dernier (pour comparaison)
        $moyenneSemestreDernier = Note::where('created_at', '<', Carbon::now()->subMonths(6))
                                     ->avg('valeur') ?? 0;
        
        $variationMoyenne = $moyenneSemestreDernier > 0
            ? round($moyenneGenerale - $moyenneSemestreDernier, 1)
            : 0;
        
        // Taux de réussite (notes >= 10)
        $totalNotes = Note::count();
        $notesReussies = Note::where('valeur', '>=', 10)->count();
        
        $tauxReussite = $totalNotes > 0 
            ? round(($notesReussies / $totalNotes) * 100, 1)
            : 0;
        
        // Taux de réussite année dernière
        $totalNotesAnneeDerniere = Note::whereYear('created_at', Carbon::now()->subYear()->year)->count();
        $notesReussiesAnneeDerniere = Note::whereYear('created_at', Carbon::now()->subYear()->year)
                                          ->where('valeur', '>=', 10)
                                          ->count();
        
        $tauxReussiteAnneeDerniere = $totalNotesAnneeDerniere > 0
            ? round(($notesReussiesAnneeDerniere / $totalNotesAnneeDerniere) * 100, 1)
            : 0;
        
        $variationTauxReussite = round($tauxReussite - $tauxReussiteAnneeDerniere, 1);
        
        return [
            'etudiants' => [
                'total' => $totalEtudiants,
                'variation' => $variationEtudiants,
                'label' => $variationEtudiants >= 0 ? '+' . $variationEtudiants . '% vs mois dernier' : $variationEtudiants . '% vs mois dernier',
            ],
            'enseignants' => [
                'total' => $totalEnseignants,
                'nouveaux' => $nouveauxEnseignants,
                'label' => '+' . $nouveauxEnseignants . ' nouveaux ce mois',
            ],
            'moyenne' => [
                'valeur' => $moyenneGenerale,
                'variation' => $variationMoyenne,
                'label' => ($variationMoyenne >= 0 ? '+' : '') . $variationMoyenne . ' vs semestre dernier',
            ],
            'reussite' => [
                'taux' => $tauxReussite,
                'variation' => $variationTauxReussite,
                'label' => ($variationTauxReussite >= 0 ? '+' : '') . $variationTauxReussite . '% vs année dernière',
            ],
        ];
    }
    
    /**
     * Obtenir la performance par filière
     */
    private function getPerformanceParFiliere()
    {
        return Filiere::with(['inscriptions.etudiant.notes.matiere'])
            ->get()
            ->map(function ($filiere) {
                // Récupérer tous les étudiants de la filière
                $etudiants = $filiere->inscriptions->pluck('etudiant')->unique('id');
                $nbEtudiants = $etudiants->count();
                
                if ($nbEtudiants === 0) {
                    return null;
                }
                
                // Calculer la moyenne générale de la filière
                $toutesLesNotes = $etudiants->flatMap(function ($etudiant) {
                    return $etudiant->notes;
                });
                
                $moyenneFiliere = $toutesLesNotes->avg('valeur') ?? 0;
                
                // Calculer le taux de réussite
                $notesReussies = $toutesLesNotes->where('valeur', '>=', 10)->count();
                $totalNotes = $toutesLesNotes->count();
                
                $tauxReussite = $totalNotes > 0 
                    ? round(($notesReussies / $totalNotes) * 100)
                    : 0;
                
                // Déterminer la couleur selon le taux
                $couleur = $this->getCouleurReussite($tauxReussite);
                
                return [
                    'nom' => $filiere->nom,
                    'nb_etudiants' => $nbEtudiants,
                    'moyenne' => round($moyenneFiliere, 1),
                    'taux_reussite' => $tauxReussite,
                    'couleur' => $couleur,
                ];
            })
            ->filter() // Retirer les valeurs null
            ->sortByDesc('taux_reussite')
            ->take(10) // Top 10 filières
            ->values();
    }
    
    /**
     * Déterminer la couleur selon le taux de réussite
     */
    private function getCouleurReussite($taux)
    {
        if ($taux >= 80) return '#10b981'; // Vert
        if ($taux >= 70) return '#f59e0b'; // Orange
        return '#ef4444'; // Rouge
    }
    
    /**
     * Obtenir les activités récentes
     */
    private function getActivitesRecentes()
    {
        $activites = collect();
        
        // 1. Nouvelles filières créées
        $nouvellesFilieres = Filiere::latest()
            ->take(2)
            ->get()
            ->map(function ($filiere) {
                return [
                    'type' => 'filiere',
                    'titre' => 'Nouvelle filière créée',
                    'description' => $filiere->nom,
                    'auteur' => 'Admin',
                    'date' => $filiere->created_at,
                    'couleur' => 'green',
                ];
            });
        
        // 2. Notes récemment saisies
        $notesRecentes = Note::with(['matiere', 'enseignant'])
            ->latest('date_saisie')
            ->take(3)
            ->get()
            ->map(function ($note) {
                return [
                    'type' => 'note',
                    'titre' => 'Notes saisies - ' . $note->matiere->nom,
                    'description' => $note->type_evaluation,
                    'auteur' => $note->enseignant->nom_complet ?? 'Enseignant',
                    'date' => $note->date_saisie,
                    'couleur' => 'blue',
                ];
            });
        
        // 3. Nouveaux enseignants
        $nouveauxEnseignants = Enseignant::latest()
            ->take(2)
            ->get()
            ->map(function ($enseignant) {
                return [
                    'type' => 'enseignant',
                    'titre' => 'Nouvel enseignant ajouté',
                    'description' => $enseignant->nom_complet,
                    'auteur' => 'Admin',
                    'date' => $enseignant->created_at,
                    'couleur' => 'green',
                ];
            });
        
        // Fusionner et trier par date
        return $activites
            ->concat($nouvellesFilieres)
            ->concat($notesRecentes)
            ->concat($nouveauxEnseignants)
            ->sortByDesc('date')
            ->take(5)
            ->map(function ($activite) {
                $activite['date_relative'] = $this->getDateRelative($activite['date']);
                return $activite;
            })
            ->values();
    }
    
    /**
     * Convertir une date en format relatif (ex: "il y a 2h")
     */
    private function getDateRelative($date)
    {
        $diff = Carbon::now()->diff($date);
        
        if ($diff->y > 0) {
            return $diff->y . 'a';
        } elseif ($diff->m > 0) {
            return $diff->m . 'mois';
        } elseif ($diff->d > 0) {
            return $diff->d . 'j';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h';
        } elseif ($diff->i > 0) {
            return $diff->i . 'min';
        } else {
            return 'maintenant';
        }
    }
}