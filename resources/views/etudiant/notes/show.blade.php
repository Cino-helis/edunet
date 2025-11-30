@extends('layouts.dashboard')

@section('title', 'Détail de la note')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('etudiant.notes.index') }}" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-1">Détail de la note</h2>
            <p class="text-muted mb-0">{{ $note->matiere->nom }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Informations de la note -->
        <div class="col-lg-8">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-5 text-center">
                    <!-- Note -->
                    <div class="mb-4">
                        <div class="d-inline-block rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; background: {{ $note->valeur >= 10 ? '#dcfce7' : '#fee2e2' }};">
                            <div>
                                <div class="fw-bold" style="font-size: 4rem; color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                    {{ number_format($note->valeur, 2) }}
                                </div>
                                <div class="text-muted" style="margin-top: -10px;">/20</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    @if($note->valeur >= 16)
                        <h4 class="fw-bold mb-2" style="color: #16a34a;">
                            <i class="bi bi-trophy-fill me-2"></i>Excellent !
                        </h4>
                        <p class="text-muted">Vous avez obtenu une excellente note</p>
                    @elseif($note->valeur >= 14)
                        <h4 class="fw-bold mb-2" style="color: #0284c7;">
                            <i class="bi bi-star-fill me-2"></i>Très bien !
                        </h4>
                        <p class="text-muted">Une très belle performance</p>
                    @elseif($note->valeur >= 12)
                        <h4 class="fw-bold mb-2" style="color: #4f46e5;">
                            <i class="bi bi-check-circle-fill me-2"></i>Bien
                        </h4>
                        <p class="text-muted">Un bon résultat</p>
                    @elseif($note->valeur >= 10)
                        <h4 class="fw-bold mb-2" style="color: #d97706;">
                            <i class="bi bi-check me-2"></i>Passable
                        </h4>
                        <p class="text-muted">Vous avez validé cette évaluation</p>
                    @else
                        <h4 class="fw-bold mb-2" style="color: #dc2626;">
                            <i class="bi bi-x-circle-fill me-2"></i>Insuffisant
                        </h4>
                        <p class="text-muted">Il faudra progresser pour la prochaine fois</p>
                    @endif
                </div>
            </div>

            <!-- Informations détaillées -->
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Matière -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #dbeafe;">
                                    <i class="bi bi-book fs-5" style="color: #0284c7;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Matière</small>
                                    <div class="fw-bold">{{ $note->matiere->nom }}</div>
                                    <small class="text-muted">{{ $note->matiere->code }} - {{ $note->matiere->credits }} ECTS</small>
                                </div>
                            </div>
                        </div>

                        <!-- Type d'évaluation -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #fef3c7;">
                                    <i class="bi bi-clipboard-check fs-5" style="color: #f59e0b;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Type d'évaluation</small>
                                    <div class="fw-bold">{{ $note->type_evaluation }}</div>
                                    <small class="text-muted">Coefficient {{ $note->matiere->coefficient }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Enseignant -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #dcfce7;">
                                    <i class="bi bi-person fs-5" style="color: #16a34a;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Enseignant</small>
                                    <div class="fw-bold">{{ $note->enseignant->nom_complet }}</div>
                                    <small class="text-muted">{{ $note->enseignant->specialite ?? 'Enseignant' }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Date de saisie -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #e0e7ff;">
                                    <i class="bi bi-calendar-event fs-5" style="color: #4f46e5;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Date de saisie</small>
                                    <div class="fw-bold">{{ $note->date_saisie->format('d/m/Y à H:i') }}</div>
                                    <small class="text-muted">{{ $note->date_saisie->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Semestre -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #fee2e2;">
                                    <i class="bi bi-bookmark fs-5" style="color: #dc2626;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Semestre</small>
                                    <div class="fw-bold">Semestre {{ $note->matiere->semestre }}</div>
                                    <small class="text-muted">Année {{ $note->annee_academique }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Type de matière -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: #f3e8ff;">
                                    <i class="bi bi-tag fs-5" style="color: #9333ea;"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Type de matière</small>
                                    <div class="fw-bold">{{ ucfirst($note->matiere->type) }}</div>
                                    <small class="text-muted">Matière {{ $note->matiere->type }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Statistiques -->
        <div class="col-lg-4">
            <!-- Statistiques de la matière -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up text-primary me-2"></i>Statistiques de la matière
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Votre moyenne</span>
                            <strong class="fs-4" style="color: {{ $stats['moyenne_matiere'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                {{ $stats['moyenne_matiere'] }}/20
                            </strong>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px;">
                            <div class="progress-bar" 
                                 style="width: {{ ($stats['moyenne_matiere'] / 20) * 100 }}%; background: {{ $stats['moyenne_matiere'] >= 10 ? '#16a34a' : '#ef4444' }};">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted">Notes enregistrées</span>
                        <strong class="text-primary">{{ $stats['nb_notes_matiere'] }}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Meilleure note</span>
                        <strong class="text-success">{{ $stats['meilleure_note'] }}/20</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Plus basse note</span>
                        <strong class="text-danger">{{ $stats['pire_note'] }}/20</strong>
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Conseil
                    </h6>
                    @if($note->valeur >= 14)
                        <p class="mb-0 small">
                            Excellent travail ! Continuez sur cette lancée et maintenez ce niveau d'excellence. 
                            Votre régularité et votre implication sont exemplaires.
                        </p>
                    @elseif($note->valeur >= 10)
                        <p class="mb-0 small">
                            Bon résultat ! Pour progresser davantage, n'hésitez pas à approfondir vos révisions 
                            et à participer activement en cours.
                        </p>
                    @else
                        <p class="mb-0 small">
                            Ne vous découragez pas ! Identifiez les points à améliorer et n'hésitez pas à 
                            solliciter l'aide de votre enseignant. Vous pouvez progresser !
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="d-grid gap-2 mt-4">
                <a href="{{ route('etudiant.notes.index') }}" class="btn btn-outline-primary" style="border-radius: 10px;">
                    <i class="bi bi-arrow-left me-2"></i>Retour aux notes
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary" style="border-radius: 10px;">
                    <i class="bi bi-printer me-2"></i>Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .sidebar, .top-header { display: none !important; }
        .main-content { margin-left: 0 !important; }
    }
</style>
@endsection