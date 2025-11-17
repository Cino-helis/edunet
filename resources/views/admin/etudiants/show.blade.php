@extends('layouts.dashboard')

@section('title', 'Détails Étudiant')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="fw-bold mb-1">Profil de l'Étudiant</h2>
            <p class="text-muted mb-0">Informations détaillées</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.etudiants.edit', $etudiant) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Modifier
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="bi bi-trash me-2"></i>Supprimer
            </button>
        </div>
        
        <form id="delete-form" action="{{ route('admin.etudiants.destroy', $etudiant) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Informations principales -->
        <div class="col-lg-4">
            <!-- Carte Profil -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <span class="text-primary fw-bold fs-1">
                            {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($etudiant->nom, 0, 1)) }}
                        </span>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $etudiant->nom_complet }}</h4>
                    <p class="text-muted mb-3">{{ $etudiant->matricule }}</p>
                    <span class="badge bg-success px-3 py-2">Étudiant Actif</span>
                </div>
            </div>

            <!-- Carte Informations -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Email</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span>{{ $etudiant->user->email }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Date de naissance</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar text-primary me-2"></i>
                            <span>{{ $etudiant->date_naissance->format('d/m/Y') }} ({{ $etudiant->age }} ans)</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Lieu de naissance</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <span>{{ $etudiant->lieu_naissance }}</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Inscrit depuis</small>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span>{{ $etudiant->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Détails académiques -->
        <div class="col-lg-8">
            <!-- Inscriptions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-mortarboard text-primary me-2"></i>Inscriptions
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($etudiant->inscriptions as $inscription)
                        <div class="d-flex align-items-center p-3 mb-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $inscription->filiere->nom }}</h6>
                                <small class="text-muted">
                                    {{ $inscription->niveau->nom }} - {{ $inscription->annee_academique }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $inscription->statut == 'validee' ? 'success' : 'warning' }} px-3 py-2">
                                {{ ucfirst($inscription->statut) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            <p class="mb-0">Aucune inscription enregistrée</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Notes récentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-journal-text text-primary me-2"></i>Notes Récentes
                    </h5>
                    <span class="badge bg-primary">{{ $etudiant->notes->count() }} notes</span>
                </div>
                <div class="card-body p-0">
                    @forelse($etudiant->notes->take(10) as $note)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $note->matiere->nom }}</h6>
                                <small class="text-muted">
                                    {{ $note->type_evaluation }} - {{ $note->date_saisie->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="fs-4 fw-bold {{ $note->valeur >= 10 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($note->valeur, 2) }}/20
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-clipboard-x fs-3 d-block mb-2"></i>
                            <p class="mb-0">Aucune note enregistrée</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1">Moyenne générale</small>
                                    <h3 class="mb-0 fw-bold text-primary">
                                        {{ $etudiant->notes->count() > 0 ? number_format($etudiant->notes->avg('valeur'), 2) : 'N/A' }}
                                    </h3>
                                </div>
                                <i class="bi bi-star-fill fs-1 text-warning opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1">Notes obtenues</small>
                                    <h3 class="mb-0 fw-bold text-success">{{ $etudiant->notes->count() }}</h3>
                                </div>
                                <i class="bi bi-clipboard-check-fill fs-1 text-success opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1">Taux de réussite</small>
                                    <h3 class="mb-0 fw-bold text-info">
                                        @if($etudiant->notes->count() > 0)
                                            {{ number_format(($etudiant->notes->where('valeur', '>=', 10)->count() / $etudiant->notes->count()) * 100, 1) }}%
                                        @else
                                            N/A
                                        @endif
                                    </h3>
                                </div>
                                <i class="bi bi-trophy-fill fs-1 text-info opacity-25"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action est irréversible et supprimera toutes les données associées.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
@endsection