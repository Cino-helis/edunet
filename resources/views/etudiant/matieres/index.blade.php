@extends('layouts.dashboard')

@section('title', 'Mes Matières')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Mes Matières</h2>
        <p class="text-muted mb-0">Consultez vos matières et leurs résultats</p>
    </div>

    @if(!$inscriptionActive)
        <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Aucune inscription active trouvée pour l'année 2025-2026
        </div>
    @else

    <!-- Info inscription -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-2" style="color: #0369a1;">
                        <i class="bi bi-mortarboard me-2"></i>Votre cursus actuel
                    </h5>
                    <p class="mb-0" style="color: #075985;">
                        <strong>{{ $inscriptionActive->filiere->nom }}</strong> - 
                        {{ $inscriptionActive->niveau->nom }} - 
                        Année {{ $inscriptionActive->annee_academique }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="text-muted small">Crédits requis</div>
                    <h4 class="fw-bold mb-0" style="color: #0369a1;">
                        {{ $inscriptionActive->niveau->credits_requis }} ECTS
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    @if($stats)
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #dbeafe;">
                            <i class="bi bi-book-fill fs-4" style="color: #0284c7;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Matières</small>
                            <h4 class="mb-0 fw-bold">{{ $stats['total_matieres'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #dcfce7;">
                            <i class="bi bi-check-circle-fill fs-4" style="color: #16a34a;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Validées</small>
                            <h4 class="mb-0 fw-bold text-success">{{ $stats['matieres_validees'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #fef3c7;">
                            <i class="bi bi-star-fill fs-4" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Moyenne</small>
                            <h4 class="mb-0 fw-bold">{{ $stats['moyenne_generale'] ? number_format($stats['moyenne_generale'], 1) : 'N/A' }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #f3e8ff;">
                            <i class="bi bi-award-fill fs-4" style="color: #9333ea;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Crédits</small>
                            <h4 class="mb-0 fw-bold text-primary">{{ $stats['credits_obtenus'] }}<small class="fs-6 text-muted">/{{ $stats['total_credits'] }}</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('etudiant.matieres.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Filtrer par semestre</label>
                    <select name="semestre" class="form-select" style="border-radius: 10px;" onchange="this.form.submit()">
                        <option value="">Tous les semestres</option>
                        <option value="1" {{ $semestreFiltre == '1' ? 'selected' : '' }}>Semestre 1</option>
                        <option value="2" {{ $semestreFiltre == '2' ? 'selected' : '' }}>Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Filtrer par type</label>
                    <select name="type" class="form-select" style="border-radius: 10px;" onchange="this.form.submit()">
                        <option value="">Tous les types</option>
                        <option value="obligatoire" {{ $typeFiltre == 'obligatoire' ? 'selected' : '' }}>Obligatoire</option>
                        <option value="optionnelle" {{ $typeFiltre == 'optionnelle' ? 'selected' : '' }}>Optionnelle</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('etudiant.matieres.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 10px;">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des matières -->
    <div class="row g-3">
        @forelse($matieresAvecStats as $item)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <!-- En-tête matière -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $item['matiere']->nom }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge" style="background: #dbeafe; color: #1e40af; border-radius: 6px;">
                                        {{ $item['matiere']->code }}
                                    </span>
                                    <span class="badge" style="background: #f3e8ff; color: #7c3aed; border-radius: 6px;">
                                        S{{ $item['matiere']->semestre }}
                                    </span>
                                    @if($item['matiere']->type == 'obligatoire')
                                        <span class="badge" style="background: #fee2e2; color: #dc2626; border-radius: 6px;">
                                            <i class="bi bi-exclamation-circle me-1"></i>Obligatoire
                                        </span>
                                    @else
                                        <span class="badge" style="background: #fef3c7; color: #d97706; border-radius: 6px;">
                                            <i class="bi bi-star me-1"></i>Optionnelle
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($item['moyenne'] !== null)
                                <div class="text-end">
                                    <div class="fw-bold" style="font-size: 2rem; color: {{ $item['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                        {{ number_format($item['moyenne'], 1) }}
                                    </div>
                                    <small class="text-muted">/20</small>
                                </div>
                            @endif
                        </div>

                        <!-- Enseignant -->
                        @if($item['enseignant'])
                            <div class="mb-3 p-3 rounded" style="background: #f8f9fa;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Enseignant</small>
                                        <div class="fw-semibold">{{ $item['enseignant']->nom_complet }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informations -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 rounded" style="background: #f8f9fa;">
                                    <i class="bi bi-award text-primary mb-1 d-block"></i>
                                    <div class="fw-bold">{{ $item['matiere']->credits }} ECTS</div>
                                    <small class="text-muted" style="font-size: 0.7rem;">Crédits</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 rounded" style="background: #f8f9fa;">
                                    <i class="bi bi-calculator text-primary mb-1 d-block"></i>
                                    <div class="fw-bold">{{ $item['matiere']->coefficient }}</div>
                                    <small class="text-muted" style="font-size: 0.7rem;">Coefficient</small>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded" style="background: #f8f9fa;">
                            <div>
                                <small class="text-muted d-block">Notes enregistrées</small>
                                <span class="fw-bold">{{ $item['nb_notes'] }} note(s)</span>
                            </div>
                            @if($item['moyenne'] !== null)
                                <span class="badge px-3 py-2" style="background: {{ $item['moyenne'] >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $item['moyenne'] >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 8px;">
                                    <i class="bi bi-{{ $item['moyenne'] >= 10 ? 'check-circle' : 'x-circle' }} me-1"></i>
                                    {{ $item['statut'] }}
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="background: #e5e7eb; color: #6b7280; border-radius: 8px;">
                                    En cours
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <a href="{{ route('etudiant.matieres.show', $item['matiere']->id) }}" 
                           class="btn btn-outline-primary w-100" style="border-radius: 10px;">
                            <i class="bi bi-eye me-2"></i>Voir les détails
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">Aucune matière trouvée</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @endif
</div>
@endsection