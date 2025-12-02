@extends('layouts.dashboard')

@section('title', 'Mon Emploi du Temps')

@section('content')
<div>
    <!-- Header avec actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Mon Emploi du Temps</h2>
            <p class="text-muted mb-0">Planning hebdomadaire des cours</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary" onclick="window.print()" style="border-radius: 10px 0 0 10px;">
                <i class="bi bi-printer me-2"></i>Imprimer
            </button>
            <button class="btn btn-primary" style="border-radius: 0 10px 10px 0;">
                <i class="bi bi-download me-2"></i>Exporter
            </button>
        </div>
    </div>

    <!-- Informations du cursus -->
    @if($inscriptionActive)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <i class="bi bi-calendar-week fs-3" style="color: #0284c7;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: #0369a1;">
                                {{ $inscriptionActive->filiere->nom }} - {{ $inscriptionActive->niveau->nom }}
                            </h5>
                            <p class="mb-0" style="color: #075985;">
                                Année académique {{ $inscriptionActive->annee_academique }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="mb-1">
                        <span class="badge px-3 py-2" style="background: white; color: #0369a1; border-radius: 10px; font-size: 0.9rem;">
                            <i class="bi bi-clock me-1"></i>{{ $totalHeures ?? 0 }}h / semaine
                        </span>
                    </div>
                    <small style="color: #075985;">
                        <i class="bi bi-book me-1"></i>{{ $nbMatieres ?? 0 }} matières
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation semaine -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-outline-secondary" style="border-radius: 10px;">
                    <i class="bi bi-chevron-left"></i>
                </button>
                
                <div class="text-center">
                    <h5 class="fw-bold mb-1">{{ $semaineActuelle ?? 'Semaine du 2 au 6 Décembre 2024' }}</h5>
                    <small class="text-muted">Semestre {{ $inscriptionActive->niveau->filiere->duree_annees <= 3 ? '1' : '2' }}</small>
                </div>
                
                <button class="btn btn-outline-secondary" style="border-radius: 10px;">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Vue sélection (Semaine / Jour) -->
    <div class="mb-4">
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="vue" id="vue-semaine" checked>
            <label class="btn btn-outline-primary" for="vue-semaine" style="border-radius: 10px 0 0 10px;">
                <i class="bi bi-calendar-week me-2"></i>Vue Semaine
            </label>

            <input type="radio" class="btn-check" name="vue" id="vue-jour">
            <label class="btn btn-outline-primary" for="vue-jour" style="border-radius: 0 10px 10px 0;">
                <i class="bi bi-calendar-day me-2"></i>Vue Jour
            </label>
        </div>
    </div>

    <!-- Emploi du temps - Vue Semaine -->
    <div id="vue-semaine-content">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table emploi-temps-table mb-0">
                        <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th class="border-end" style="width: 120px; min-width: 120px;">
                                    <div class="text-center py-3">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                </th>
                                @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                                <th class="text-center border-end">
                                    <div class="py-3">
                                        <div class="fw-bold">{{ $jour }}</div>
                                        <small class="text-muted">{{ date('d/m', strtotime('monday this week +' . ($loop->index) . ' days')) }}</small>
                                    </div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            // Horaires de 7h à 18h
                            $horaires = [
                                '07:00 - 08:30',
                                '08:30 - 10:00',
                                '10:00 - 11:30',
                                '11:30 - 13:00',
                                '13:00 - 14:30',
                                '14:30 - 16:00',
                                '16:00 - 17:30',
                                '17:30 - 19:00'
                            ];
                            
                            // Exemple de données (À remplacer par les vraies données)
                            $coursExemple = [
                                'Lundi' => [
                                    '08:30 - 10:00' => ['matiere' => 'Programmation Web', 'type' => 'Cours', 'salle' => 'A101', 'prof' => 'M. Dupont', 'color' => '#dbeafe'],
                                    '10:00 - 11:30' => ['matiere' => 'Programmation Web', 'type' => 'TP', 'salle' => 'Lab 2', 'prof' => 'M. Dupont', 'color' => '#dcfce7'],
                                    '14:30 - 16:00' => ['matiere' => 'Base de Données', 'type' => 'Cours', 'salle' => 'B203', 'prof' => 'Mme. Martin', 'color' => '#fef3c7'],
                                ],
                                'Mardi' => [
                                    '08:30 - 10:00' => ['matiere' => 'Mathématiques', 'type' => 'Cours', 'salle' => 'C105', 'prof' => 'M. Bernard', 'color' => '#e0e7ff'],
                                    '13:00 - 14:30' => ['matiere' => 'Anglais', 'type' => 'TD', 'salle' => 'D301', 'prof' => 'Mrs. Smith', 'color' => '#fce7f3'],
                                ],
                                'Mercredi' => [
                                    '10:00 - 11:30' => ['matiere' => 'Réseaux', 'type' => 'Cours', 'salle' => 'A201', 'prof' => 'M. Leroy', 'color' => '#e0f2fe'],
                                    '14:30 - 16:00' => ['matiere' => 'Réseaux', 'type' => 'TP', 'salle' => 'Lab 1', 'prof' => 'M. Leroy', 'color' => '#dcfce7'],
                                ],
                                'Jeudi' => [
                                    '08:30 - 10:00' => ['matiere' => 'Algorithmique', 'type' => 'Cours', 'salle' => 'B101', 'prof' => 'Mme. Dubois', 'color' => '#f3e8ff'],
                                    '10:00 - 11:30' => ['matiere' => 'Algorithmique', 'type' => 'TD', 'salle' => 'B102', 'prof' => 'Mme. Dubois', 'color' => '#dcfce7'],
                                ],
                                'Vendredi' => [
                                    '08:30 - 10:00' => ['matiere' => 'Gestion Projet', 'type' => 'Cours', 'salle' => 'C201', 'prof' => 'M. Petit', 'color' => '#fee2e2'],
                                    '13:00 - 14:30' => ['matiere' => 'Sport', 'type' => 'Activité', 'salle' => 'Gymnase', 'prof' => 'Coach', 'color' => '#fef3c7'],
                                ],
                            ];
                            @endphp
                            
                            @foreach($horaires as $horaire)
                            <tr style="height: 80px;">
                                <td class="border-end text-center align-middle" style="background: #fafafa;">
                                    <div class="fw-semibold small">{{ $horaire }}</div>
                                </td>
                                
                                @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                                <td class="border-end p-2 align-top">
                                    @if(isset($coursExemple[$jour][$horaire]))
                                        @php $cours = $coursExemple[$jour][$horaire]; @endphp
                                        <div class="cours-card" style="background: {{ $cours['color'] }}; border-left: 4px solid {{ $cours['color'] == '#dbeafe' ? '#0284c7' : ($cours['color'] == '#dcfce7' ? '#16a34a' : ($cours['color'] == '#fef3c7' ? '#f59e0b' : ($cours['color'] == '#e0e7ff' ? '#4f46e5' : ($cours['color'] == '#fce7f3' ? '#ec4899' : ($cours['color'] == '#f3e8ff' ? '#9333ea' : '#ef4444'))))) }}; border-radius: 8px; padding: 8px; height: 100%; cursor: pointer; transition: all 0.2s;" onclick="afficherDetailCours(this)">
                                            <div class="fw-bold small mb-1">{{ $cours['matiere'] }}</div>
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <span class="badge" style="background: rgba(0,0,0,0.1); font-size: 0.65rem; padding: 2px 6px;">
                                                    {{ $cours['type'] }}
                                                </span>
                                            </div>
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-door-open" style="font-size: 0.7rem;"></i>
                                                <span style="font-size: 0.7rem;">{{ $cours['salle'] }}</span>
                                            </div>
                                            <div class="small text-muted d-flex align-items-center gap-1 mt-1">
                                                <i class="bi bi-person" style="font-size: 0.7rem;"></i>
                                                <span style="font-size: 0.7rem;">{{ $cours['prof'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Emploi du temps - Vue Jour (caché par défaut) -->
    <div id="vue-jour-content" style="display: none;">
        <div class="row g-4">
            <!-- Sélection du jour -->
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-3">
                        <div class="d-flex gap-2 overflow-auto">
                            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $jour)
                            <button class="btn btn-outline-primary flex-shrink-0 {{ $loop->first ? 'active' : '' }}" 
                                    style="border-radius: 10px; min-width: 120px;">
                                <div class="fw-semibold">{{ $jour }}</div>
                                <small>{{ date('d/m', strtotime('monday this week +' . ($loop->index) . ' days')) }}</small>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des cours du jour -->
            <div class="col-12">
                <div class="row g-3">
                    @php
                    $coursLundi = [
                        ['horaire' => '08:30 - 10:00', 'matiere' => 'Programmation Web', 'type' => 'Cours', 'salle' => 'A101', 'prof' => 'M. Dupont', 'color' => '#dbeafe'],
                        ['horaire' => '10:00 - 11:30', 'matiere' => 'Programmation Web', 'type' => 'TP', 'salle' => 'Lab 2', 'prof' => 'M. Dupont', 'color' => '#dcfce7'],
                        ['horaire' => '14:30 - 16:00', 'matiere' => 'Base de Données', 'type' => 'Cours', 'salle' => 'B203', 'prof' => 'Mme. Martin', 'color' => '#fef3c7'],
                    ];
                    @endphp

                    @forelse($coursLundi as $cours)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; border-left: 4px solid {{ $cours['color'] == '#dbeafe' ? '#0284c7' : ($cours['color'] == '#dcfce7' ? '#16a34a' : '#f59e0b') }} !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1">{{ $cours['matiere'] }}</h5>
                                        <span class="badge px-3 py-1" style="background: {{ $cours['color'] }}; color: {{ $cours['color'] == '#dbeafe' ? '#0284c7' : ($cours['color'] == '#dcfce7' ? '#16a34a' : '#f59e0b') }}; border-radius: 6px;">
                                            {{ $cours['type'] }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary">{{ $cours['horaire'] }}</div>
                                        <small class="text-muted">1h30</small>
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: #f8f9fa;">
                                        <i class="bi bi-door-open text-primary"></i>
                                        <span class="small">Salle {{ $cours['salle'] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: #f8f9fa;">
                                        <i class="bi bi-person-badge text-success"></i>
                                        <span class="small">{{ $cours['prof'] }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-top">
                                    <button class="btn btn-sm btn-outline-primary w-100" style="border-radius: 8px;">
                                        <i class="bi bi-info-circle me-2"></i>Plus de détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Aucun cours prévu pour ce jour</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-palette text-primary me-2"></i>Légende
            </h6>
            <div class="row g-3">
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #dbeafe; border: 2px solid #0284c7;"></div>
                        <small>Cours Magistral</small>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #dcfce7; border: 2px solid #16a34a;"></div>
                        <small>Travaux Pratiques</small>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #fef3c7; border: 2px solid #f59e0b;"></div>
                        <small>Travaux Dirigés</small>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #e0e7ff; border: 2px solid #4f46e5;"></div>
                        <small>Conférence</small>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #fee2e2; border: 2px solid #ef4444;"></div>
                        <small>Examen</small>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded" style="width: 20px; height: 20px; background: #f3e8ff; border: 2px solid #9333ea;"></div>
                        <small>Projet</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Aucune inscription active trouvée. Veuillez contacter l'administration.
    </div>
    @endif
</div>

<!-- Modal Détail du Cours -->
<div class="modal fade" id="modalDetailCours" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitre">Détails du cours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0" id="modalBody">
                <!-- Contenu dynamique -->
            </div>
        </div>
    </div>
</div>

<style>
    .emploi-temps-table {
        table-layout: fixed;
    }
    
    .emploi-temps-table th,
    .emploi-temps-table td {
        border: 1px solid #e5e7eb;
        vertical-align: top;
    }
    
    .cours-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    @media print {
        .btn, .sidebar, .top-header { 
            display: none !important; 
        }
        .main-content { 
            margin-left: 0 !important; 
        }
        .card { 
            border: 1px solid #000 !important; 
            page-break-inside: avoid; 
        }
    }
    
    @media (max-width: 768px) {
        .emploi-temps-table {
            font-size: 0.8rem;
        }
        
        .cours-card {
            font-size: 0.75rem;
        }
    }
</style>

<script>
    // Basculer entre vue semaine et vue jour
    document.getElementById('vue-semaine').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('vue-semaine-content').style.display = 'block';
            document.getElementById('vue-jour-content').style.display = 'none';
        }
    });
    
    document.getElementById('vue-jour').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('vue-semaine-content').style.display = 'none';
            document.getElementById('vue-jour-content').style.display = 'block';
        }
    });
    
    // Afficher les détails d'un cours
    function afficherDetailCours(element) {
        const matiere = element.querySelector('.fw-bold').textContent;
        const type = element.querySelector('.badge').textContent.trim();
        const salle = element.querySelectorAll('.small.text-muted span')[0].textContent;
        const prof = element.querySelectorAll('.small.text-muted span')[1].textContent;
        
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <div class="p-3">
                <div class="mb-4">
                    <h5 class="fw-bold mb-2">${matiere}</h5>
                    <span class="badge bg-primary px-3 py-2">${type}</span>
                </div>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: #f8f9fa;">
                        <i class="bi bi-door-open fs-4 text-primary"></i>
                        <div>
                            <small class="text-muted d-block">Salle</small>
                            <div class="fw-semibold">${salle}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: #f8f9fa;">
                        <i class="bi bi-person-badge fs-4 text-success"></i>
                        <div>
                            <small class="text-muted d-block">Enseignant</small>
                            <div class="fw-semibold">${prof}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: #f8f9fa;">
                        <i class="bi bi-clock fs-4 text-warning"></i>
                        <div>
                            <small class="text-muted d-block">Durée</small>
                            <div class="fw-semibold">1h30</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 rounded" style="background: #e0f2fe;">
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-info-circle me-1"></i>Informations complémentaires
                    </small>
                    <p class="small mb-0">
                        Pensez à apporter vos supports de cours et votre matériel nécessaire.
                    </p>
                </div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('modalDetailCours'));
        modal.show();
    }
</script>
@endsection