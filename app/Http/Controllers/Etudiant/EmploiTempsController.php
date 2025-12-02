<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EmploiTempsController extends Controller
{
    /**
     * Afficher l'emploi du temps de l'étudiant
     */
    public function index(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        // Récupérer l'inscription active
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2024-2025')
            ->with(['filiere', 'niveau'])
            ->first();
        
        if (!$inscriptionActive) {
            return view('etudiant.emploi_temps.index', [
                'inscriptionActive' => null,
                'coursParJour' => collect(),
                'semaineActuelle' => null,
                'totalHeures' => 0,
                'nbMatieres' => 0,
            ]);
        }
        
        // Déterminer la semaine à afficher
        $semaine = $request->get('semaine', 0); // 0 = semaine actuelle
        $dateDebut = Carbon::now()->startOfWeek()->addWeeks($semaine);
        $dateFin = Carbon::now()->endOfWeek()->addWeeks($semaine);
        
        // Formater la période affichée
        $semaineActuelle = 'Semaine du ' . $dateDebut->format('d') . ' au ' . $dateFin->format('d M Y');
        
        // Récupérer l'emploi du temps pour le niveau
        $emploiTemps = \App\Models\EmploiTemps::where('niveau_id', $inscriptionActive->niveau_id)
            ->where('annee_academique', '2024-2025')
            ->with(['matiere', 'enseignant'])
            ->get();
        
        // Grouper les cours par jour
        $coursParJour = $this->grouperCoursParJour($emploiTemps);
        
        // Calculer les statistiques
        $stats = $this->calculerStatistiques($emploiTemps);
        
        return view('etudiant.emploi_temps.index', [
            'etudiant' => $etudiant,
            'inscriptionActive' => $inscriptionActive,
            'coursParJour' => $coursParJour,
            'semaineActuelle' => $semaineActuelle,
            'totalHeures' => $stats['totalHeures'],
            'nbMatieres' => $stats['nbMatieres'],
            'semaine' => $semaine,
        ]);
    }
    
    /**
     * Grouper les cours par jour et par horaire
     */
    private function grouperCoursParJour($emploiTemps)
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        $coursParJour = collect();
        
        foreach ($jours as $jour) {
            $coursJour = $emploiTemps->where('jour', $jour)->map(function ($cours) {
                return [
                    'id' => $cours->id,
                    'matiere' => $cours->matiere->nom,
                    'matiere_code' => $cours->matiere->code,
                    'type' => $cours->type,
                    'horaire' => $cours->heure_debut->format('H:i') . ' - ' . $cours->heure_fin->format('H:i'),
                    'heure_debut' => $cours->heure_debut->format('H:i'),
                    'heure_fin' => $cours->heure_fin->format('H:i'),
                    'salle' => $cours->salle,
                    'professeur' => $cours->enseignant->nom_complet,
                    'color' => $this->getCouleurType($cours->type),
                    'duree' => $this->calculerDuree($cours->heure_debut, $cours->heure_fin),
                ];
            })->sortBy('heure_debut')->values();
            
            $coursParJour[$jour] = $coursJour;
        }
        
        return $coursParJour;
    }
    
    /**
     * Calculer les statistiques de l'emploi du temps
     */
    private function calculerStatistiques($emploiTemps)
    {
        // Calculer le total d'heures par semaine
        $totalMinutes = 0;
        foreach ($emploiTemps as $cours) {
            $debut = Carbon::parse($cours->heure_debut);
            $fin = Carbon::parse($cours->heure_fin);
            $totalMinutes += $debut->diffInMinutes($fin);
        }
        $totalHeures = round($totalMinutes / 60, 1);
        
        // Nombre de matières différentes
        $nbMatieres = $emploiTemps->pluck('matiere_id')->unique()->count();
        
        return [
            'totalHeures' => $totalHeures,
            'nbMatieres' => $nbMatieres,
        ];
    }
    
    /**
     * Obtenir la couleur selon le type de cours
     */
    private function getCouleurType($type)
    {
        return match($type) {
            'Cours' => '#dbeafe',      // Bleu
            'TP' => '#dcfce7',          // Vert
            'TD' => '#fef3c7',          // Jaune
            'Examen' => '#fee2e2',      // Rouge
            'Projet' => '#f3e8ff',      // Violet
            'Conférence' => '#e0e7ff',  // Indigo
            default => '#f3f4f6',       // Gris
        };
    }
    
    /**
     * Calculer la durée d'un cours
     */
    private function calculerDuree($heureDebut, $heureFin)
    {
        $debut = Carbon::parse($heureDebut);
        $fin = Carbon::parse($heureFin);
        $minutes = $debut->diffInMinutes($fin);
        
        $heures = floor($minutes / 60);
        $minutesRestantes = $minutes % 60;
        
        if ($heures > 0 && $minutesRestantes > 0) {
            return $heures . 'h' . $minutesRestantes;
        } elseif ($heures > 0) {
            return $heures . 'h';
        } else {
            return $minutesRestantes . 'min';
        }
    }
    
    /**
     * Exporter l'emploi du temps en PDF
     */
    public function exportPDF(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2024-2025')
            ->with(['filiere', 'niveau'])
            ->first();
        
        if (!$inscriptionActive) {
            return back()->with('error', 'Aucune inscription active trouvée.');
        }
        
        $emploiTemps = \App\Models\EmploiTemps::where('niveau_id', $inscriptionActive->niveau_id)
            ->where('annee_academique', '2024-2025')
            ->with(['matiere', 'enseignant'])
            ->get();
        
        $coursParJour = $this->grouperCoursParJour($emploiTemps);
        
        // TODO: Implémenter la génération PDF avec une bibliothèque comme DomPDF ou TCPDF
        // Exemple avec DomPDF :
        // $pdf = PDF::loadView('etudiant.emploi-temps.pdf', [
        //     'etudiant' => $etudiant,
        //     'inscriptionActive' => $inscriptionActive,
        //     'coursParJour' => $coursParJour,
        // ]);
        // return $pdf->download('emploi-temps.pdf');
        
        return back()->with('info', 'Export PDF en cours de développement');
    }
    
    /**
     * Exporter l'emploi du temps en iCal (pour calendrier)
     */
    public function exportIcal(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2024-2025')
            ->with(['filiere', 'niveau'])
            ->first();
        
        if (!$inscriptionActive) {
            return back()->with('error', 'Aucune inscription active trouvée.');
        }
        
        $emploiTemps = \App\Models\EmploiTemps::where('niveau_id', $inscriptionActive->niveau_id)
            ->where('annee_academique', '2024-2025')
            ->with(['matiere', 'enseignant'])
            ->get();
        
        // Générer le fichier iCal
        $ical = $this->genererIcal($emploiTemps, $etudiant);
        
        return response($ical)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="emploi-temps.ics"');
    }
    
    /**
     * Générer le contenu iCal
     */
    private function genererIcal($emploiTemps, $etudiant)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//EduNet//Emploi du Temps//FR\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        $ical .= "X-WR-CALNAME:Emploi du Temps - " . $etudiant->nom_complet . "\r\n";
        $ical .= "X-WR-TIMEZONE:Europe/Paris\r\n";
        
        // Définir la date de début de semestre (à adapter)
        $dateDebutSemestre = Carbon::parse('2024-09-02'); // Exemple : 2 septembre 2024
        $dateFinSemestre = Carbon::parse('2025-01-31');   // Exemple : 31 janvier 2025
        
        foreach ($emploiTemps as $cours) {
            // Trouver la date du prochain cours
            $joursMapping = [
                'Lundi' => Carbon::MONDAY,
                'Mardi' => Carbon::TUESDAY,
                'Mercredi' => Carbon::WEDNESDAY,
                'Jeudi' => Carbon::THURSDAY,
                'Vendredi' => Carbon::FRIDAY,
                'Samedi' => Carbon::SATURDAY,
            ];
            
            $jourCours = $joursMapping[$cours->jour];
            $dateCours = Carbon::now()->next($jourCours);
            
            // Si le cours est déjà passé cette semaine, prendre la semaine suivante
            if ($dateCours->lt(Carbon::now())) {
                $dateCours->addWeek();
            }
            
            // Combiner date et heure
            $debut = $dateCours->copy()->setTimeFromTimeString($cours->heure_debut);
            $fin = $dateCours->copy()->setTimeFromTimeString($cours->heure_fin);
            
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . md5($cours->id . $debut->timestamp) . "@edunet.com\r\n";
            $ical .= "DTSTAMP:" . $debut->format('Ymd\THis\Z') . "\r\n";
            $ical .= "DTSTART:" . $debut->format('Ymd\THis\Z') . "\r\n";
            $ical .= "DTEND:" . $fin->format('Ymd\THis\Z') . "\r\n";
            $ical .= "SUMMARY:" . $cours->matiere->nom . " (" . $cours->type . ")\r\n";
            $ical .= "DESCRIPTION:Enseignant: " . $cours->enseignant->nom_complet . "\r\n";
            $ical .= "LOCATION:" . $cours->salle . "\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            
            // Répéter chaque semaine jusqu'à la fin du semestre
            $ical .= "RRULE:FREQ=WEEKLY;UNTIL=" . $dateFinSemestre->format('Ymd\T235959\Z') . "\r\n";
            
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR\r\n";
        
        return $ical;
    }
    
    /**
     * API - Obtenir les cours d'un jour spécifique
     */
    public function getCoursJour(Request $request)
    {
        $jour = $request->get('jour', 'Lundi');
        $etudiant = auth()->user()->etudiant;
        
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2024-2025')
            ->first();
        
        if (!$inscriptionActive) {
            return response()->json(['cours' => []]);
        }
        
        $cours = \App\Models\EmploiTemps::where('niveau_id', $inscriptionActive->niveau_id)
            ->where('jour', $jour)
            ->where('annee_academique', '2024-2025')
            ->with(['matiere', 'enseignant'])
            ->orderBy('heure_debut')
            ->get()
            ->map(function ($cours) {
                return [
                    'id' => $cours->id,
                    'matiere' => $cours->matiere->nom,
                    'type' => $cours->type,
                    'horaire' => $cours->heure_debut->format('H:i') . ' - ' . $cours->heure_fin->format('H:i'),
                    'salle' => $cours->salle,
                    'professeur' => $cours->enseignant->nom_complet,
                    'color' => $this->getCouleurType($cours->type),
                ];
            });
        
        return response()->json(['cours' => $cours]);
    }
}