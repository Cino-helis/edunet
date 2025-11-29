@extends('layouts.dashboard')

@section('title', 'Tableau de bord étudiant')

@section('content')
<div>
    <!-- En-tête avec message de bienvenue -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Bonjour {{ $etudiant->prenom }} 👋
        </h2>
        <p class="text-muted mb-0">Voici un aperçu de vos résultats académiques</p>
    </div>

    <!-- Inscription active -->
    @if($inscriptionActive)
        <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-mortarboard fs-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Inscription active</h6>
                    <p class="mb-0">
                        <strong>{{ $inscriptionActive->filiere->nom }}</strong> - 
                        {{ $inscriptionActive->niveau->nom }} - 
                        Année {{ $inscriptionActive->annee_academique }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-exclamation-triangle fs-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Aucune inscription active</h6>
                    <p class="mb-0">Veuillez contacter l'administration pour vous inscrire.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques Cards -->
    <div class="row g-3 mb-4">
        <!-- Moyenne générale -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #fef3c7;">
                            <i class="bi bi-star-fill fs-4" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Moyenne générale
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">
                        {{ $stats['moyenne_generale'] }}
                        <span class="fs-5 text-muted">/20</span>
                    </h2>
                    <small class="text-{{ $stats['moyenne_generale'] >= 10 ? 'success' : 'danger' }}">
                        <i class="bi bi-{{ $stats['moyenne_generale'] >= 10 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ $stats['moyenne_generale'] >= 10 ? 'Réussite' : 'À améliorer' }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Taux de réussite -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #dcfce7;">
                            <i class="bi bi-trophy-fill fs-4" style="color: #16a34a;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Taux de réussite
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">
                        {{ $stats['taux_reussite'] }}
                        <span class="fs-5 text-muted">%</span>
                    </h2>
                    <small class="text-muted">Sur {{ $stats['total_notes'] }} notes</small>
                </div>
            </div>
        </div>

        <!-- Matières suivies -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #dbeafe;">
                            <i class="bi bi-book-fill fs-4" style="color: #0284c7;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Matières suivies
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">{{ $stats['nb_matieres'] }}</h2>
                    <small class="text-muted">{{ $stats['notes_ce_mois'] }} notes ce mois</small>
                </div>
            </div>
        </div>

        <!-- Classement -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #f3e8ff;">
                            <i class="bi bi-award-fill fs-4" style="color: #9333ea;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Classement
                        </small>
                    </div>
                    @if($classement)
                        <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">
                            {{ $classement['position'] }}
                            <span class="fs-5 text-muted">/{{ $classement['total'] }}</span>
                        </h2>
                        <small class="text-muted">Dans votre promotion</small>
                    @else
                        <h2 class="fw-bold mb-0 text-muted" style="font-size: 2rem;">N/A</h2>
                        <small class="text-muted">Pas de classement</small>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Performance par matière -->
        <div class="col-lg-8">
            <!-- Mes matières -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-book-half text-primary me-2"></i>Mes matières
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                            Voir tout
                        </a>
                    </div>

                    @if($statsParMatiere->count() > 0)
                        <div class="row g-3">
                            @foreach($statsParMatiere as $stat)
                                <div class="col-md-6">
                                    <div class="card h-100" style="border-radius: 12px; border: 1px solid #e5e7eb;">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-semibold mb-1">{{ $stat['matiere']->nom }}</h6>
                                                    <small class="text-muted">{{ $stat['matiere']->code }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <div class="fw-bold fs-4" style="color: {{ $stat['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                                        {{ $stat['moyenne'] }}<small class="fs-6">/20</small>
                                                    </div>
                                                    @if($stat['progression'] != 0)
                                                        <small class="badge" style="background: {{ $stat['progression'] > 0 ? '#dcfce7' : '#fee2e2' }}; color: {{ $stat['progression'] > 0 ? '#15803d' : '#dc2626' }};">
                                                            <i class="bi bi-{{ $stat['progression'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                                            {{ abs($stat['progression']) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="text-muted">{{ $stat['nb_notes'] }} note(s)</small>
                                                <small class="text-muted">
                                                    Coef: {{ $stat['matiere']->coefficient }} | 
                                                    {{ $stat['matiere']->credits }} ECTS
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Aucune note enregistrée pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progression mensuelle -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-graph-up text-primary me-2"></i>Évolution des 6 derniers mois
                    </h5>

                    <div class="row g-2">
                        @foreach($progression as $mois)
                            <div class="col-4 col-md-2">
                                <div class="text-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem;">
                                        {{ $mois['mois'] }}
                                    </small>
                                    @if($mois['moyenne'] !== null)
                                        <div class="fw-bold" style="color: {{ $mois['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                            {{ $mois['moyenne'] }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.65rem;">
                                            {{ $mois['nb_notes'] }} note(s)
                                        </small>
                                    @else
                                        <div class="text-muted">-</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Activités récentes -->
        <div class="col-lg-4">
            <!-- Dernières notes -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-clock-history text-primary me-2"></i>Dernières notes
                    </h5>

                    @if($notesRecentes->count() > 0)
                        <div class="timeline">
                            @foreach($notesRecentes->take(5) as $note)
                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; background: {{ $note->valeur >= 10 ? '#dcfce7' : '#fee2e2' }};">
                                            <span class="fw-bold" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }}; font-size: 0.9rem;">
                                                {{ number_format($note->valeur, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $note->matiere->nom }}</div>
                                        <small class="text-muted d-block">
                                            {{ $note->type_evaluation }} - {{ $note->date_saisie->diffForHumans() }}
                                        </small>
                                        <small class="text-muted">Par {{ $note->enseignant->nom_complet }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-3">
                            <a href="#" class="text-primary text-decoration-none fw-medium">
                                Voir toutes mes notes
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-3 text-muted d-block mb-2"></i>
                            <small class="text-muted">Aucune note récente</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top 3 de la promo -->
            @if($classement && $classement['top3']->count() > 0)
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-trophy text-warning me-2"></i>Top 3 de la promo
                        </h5>

                        @foreach($classement['top3'] as $index => $top)
                            <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded" 
                                 style="background: {{ $top['etudiant']->id == $etudiant->id ? '#eff6ff' : '#f8f9fa' }};">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" 
                                         style="width: 35px; height: 35px; background: {{ $index == 0 ? '#f59e0b' : ($index == 1 ? '#9ca3af' : '#d97706') }};">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold {{ $top['etudiant']->id == $etudiant->id ? 'text-primary' : '' }}">
                                        {{ $top['etudiant']->id == $etudiant->id ? 'Vous' : $top['etudiant']->prenom . ' ' . substr($top['etudiant']->nom, 0, 1) . '.' }}
                                    </div>
                                    <small class="text-muted">{{ $top['nb_notes'] }} notes</small>
                                </div>
                                <div class="fw-bold" style="color: #16a34a;">
                                    {{ $top['moyenne'] }}/20
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection