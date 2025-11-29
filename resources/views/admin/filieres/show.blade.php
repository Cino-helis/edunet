@extends('layouts.dashboard')

@section('title', 'Détails de la filière')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="fw-bold mb-1">{{ $filiere->nom }}</h2>
            <p class="text-muted mb-0">Détails de la filière</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Modifier
            </a>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                <i class="bi bi-trash me-2"></i>Supprimer
            </button>
        </div>
        
        <form id="delete-form" action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Informations générales -->
        <div class="col-lg-4">
            <!-- Carte Informations -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Code</small>
                        <span class="badge bg-primary fs-6">{{ $filiere->code }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Nom complet</small>
                        <div class="fw-semibold">{{ $filiere->nom }}</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Durée</small>
                        <div class="fw-semibold">{{ $filiere->duree_annees }} ans</div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Description</small>
                        <div>{{ $filiere->description ?? 'Aucune description' }}</div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Créée le</small>
                        <div>{{ $filiere->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up text-primary me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Niveaux</span>
                        <strong class="text-primary">{{ $filiere->niveaux->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Étudiants inscrits</span>
                        <strong class="text-success">{{ $filiere->inscriptions->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Enseignants affectés</span>
                        <strong class="text-info">{{ $filiere->affectations->unique('enseignant_id')->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Détails -->
        <div class="col-lg-8">
            <!-- Niveaux -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-layers text-primary me-2"></i>Niveaux ({{ $filiere->niveaux->count() }})
                    </h5>
                    <a href="{{ route('admin.niveaux.create') }}?filiere_id={{ $filiere->id }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Ajouter un niveau
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($filiere->niveaux->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Code</th>
                                        <th class="py-3">Nom</th>
                                        <th class="py-3 text-center">Ordre</th>
                                        <th class="py-3 text-center">Crédits requis</th>
                                        <th class="py-3 text-center">Matières</th>
                                        <th class="py-3 text-center">Étudiants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filiere->niveaux->sortBy('ordre') as $niveau)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary">{{ $niveau->code }}</span>
                                            </td>
                                            <td class="py-3 fw-semibold">{{ $niveau->nom }}</td>
                                            <td class="py-3 text-center">{{ $niveau->ordre }}</td>
                                            <td class="py-3 text-center">{{ $niveau->credits_requis }}</td>
                                            <td class="py-3 text-center">
                                                <a href="{{ route('admin.niveaux.matieres', $niveau) }}" class="btn btn-sm btn-outline-primary">
                                                    {{ $niveau->matieres->count() }} matière(s)
                                                </a>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="badge bg-success">{{ $niveau->inscriptions->count() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Aucun niveau créé pour cette filière</p>
                            <a href="{{ route('admin.niveaux.create') }}?filiere_id={{ $filiere->id }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Créer le premier niveau
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Étudiants inscrits -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-people text-primary me-2"></i>Étudiants inscrits ({{ $filiere->inscriptions->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($filiere->inscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3">Matricule</th>
                                        <th class="py-3">Nom complet</th>
                                        <th class="py-3">Niveau</th>
                                        <th class="py-3 text-center">Année</th>
                                        <th class="py-3 text-center">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filiere->inscriptions->take(10) as $inscription)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <span class="badge bg-primary">{{ $inscription->etudiant->matricule }}</span>
                                            </td>
                                            <td class="py-3">
                                                <a href="{{ route('admin.etudiants.show', $inscription->etudiant) }}" class="text-decoration-none">
                                                    {{ $inscription->etudiant->nom_complet }}
                                                </a>
                                            </td>
                                            <td class="py-3">{{ $inscription->niveau->nom }}</td>
                                            <td class="py-3 text-center">{{ $inscription->annee_academique }}</td>
                                            <td class="py-3 text-center">
                                                @php
                                                    $statusColors = [
                                                        'en_cours' => 'primary',
                                                        'validee' => 'success',
                                                        'suspendue' => 'warning',
                                                        'abandonnee' => 'danger',
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$inscription->statut] ?? 'secondary' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $inscription->statut)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($filiere->inscriptions->count() > 10)
                            <div class="card-footer bg-light text-center">
                                <a href="{{ route('admin.inscriptions.index') }}?filiere_id={{ $filiere->id }}" class="text-primary text-decoration-none">
                                    Voir tous les étudiants ({{ $filiere->inscriptions->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted">Aucun étudiant inscrit dans cette filière</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette filière ? Cette action supprimera également tous les niveaux et inscriptions associés.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
@endsection