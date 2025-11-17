@extends('layouts.dashboard')

@section('title', 'Gestion des Notes')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Notes</h2>
            <p class="text-muted mb-0">Liste de toutes les notes enregistrées</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.notes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Ajouter une note
            </a>
            <a href="{{ route('admin.notes.saisie-groupee') }}" class="btn btn-success">
                <i class="bi bi-list-check me-2"></i>Saisie groupée
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.notes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Matière</label>
                    <select name="matiere_id" class="form-select">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Étudiant</label>
                    <select name="etudiant_id" class="form-select">
                        <option value="">Tous les étudiants</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ request('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                                {{ $etudiant->nom_complet }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Type</label>
                    <select name="type_evaluation" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="CC" {{ request('type_evaluation') == 'CC' ? 'selected' : '' }}>CC</option>
                        <option value="TP" {{ request('type_evaluation') == 'TP' ? 'selected' : '' }}>TP</option>
                        <option value="Examen" {{ request('type_evaluation') == 'Examen' ? 'selected' : '' }}>Examen</option>
                        <option value="Projet" {{ request('type_evaluation') == 'Projet' ? 'selected' : '' }}>Projet</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Année</label>
                    <input type="text" name="annee_academique" class="form-control" 
                           placeholder="2024-2025" value="{{ request('annee_academique') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Total Notes</small>
                            <h3 class="mb-0 fw-bold">{{ $notes->total() }}</h3>
                        </div>
                        <i class="bi bi-clipboard-check fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Moyenne Générale</small>
                            <h3 class="mb-0 fw-bold">{{ number_format(\App\Models\Note::avg('valeur'), 2) }}/20</h3>
                        </div>
                        <i class="bi bi-star fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Taux Réussite</small>
                            <h3 class="mb-0 fw-bold">
                                {{ \App\Models\Note::count() > 0 ? number_format((\App\Models\Note::where('valeur', '>=', 10)->count() / \App\Models\Note::count()) * 100, 1) : 0 }}%
                            </h3>
                        </div>
                        <i class="bi bi-trophy fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Notes < 10</small>
                            <h3 class="mb-0 fw-bold text-danger">{{ \App\Models\Note::where('valeur', '<', 10)->count() }}</h3>
                        </div>
                        <i class="bi bi-x-circle fs-1 text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Étudiant</th>
                            <th class="py-3">Matière</th>
                            <th class="py-3 text-center">Type</th>
                            <th class="py-3 text-center">Note</th>
                            <th class="py-3">Enseignant</th>
                            <th class="py-3">Date</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold">{{ $note->etudiant->nom_complet }}</div>
                                    <small class="text-muted">{{ $note->etudiant->matricule }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-primary">{{ $note->matiere->code }}</span>
                                    <span class="ms-2">{{ $note->matiere->nom }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge {{ $note->type_evaluation == 'Examen' ? 'bg-danger' : 'bg-info' }}">
                                        {{ $note->type_evaluation }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="fs-5 fw-bold {{ $note->valeur >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($note->valeur, 2) }}/20
                                    </span>
                                </td>
                                <td class="py-3">
                                    <small>{{ $note->enseignant->nom_complet }}</small>
                                </td>
                                <td class="py-3">
                                    <small>{{ $note->date_saisie->format('d/m/Y H:i') }}</small>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.notes.edit', $note) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $note->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $note->id }}" 
                                          action="{{ route('admin.notes.destroy', $note) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Aucune note trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $notes->withQueryString()->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette note ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection