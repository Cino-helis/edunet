@extends('layouts.dashboard')

@section('title', 'Gestion des Matières')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Matières</h2>
            <p class="text-muted mb-0">Liste de toutes les matières enseignées</p>
        </div>
        <a href="{{ route('admin.matieres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajouter une matière
        </a>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.matieres.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Rechercher par code ou nom..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="semestre" class="form-select">
                        <option value="">Tous les semestres</option>
                        <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                        <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                        <option value="3" {{ request('semestre') == '3' ? 'selected' : '' }}>Semestre 3</option>
                        <option value="4" {{ request('semestre') == '4' ? 'selected' : '' }}>Semestre 4</option>
                        <option value="5" {{ request('semestre') == '5' ? 'selected' : '' }}>Semestre 5</option>
                        <option value="6" {{ request('semestre') == '6' ? 'selected' : '' }}>Semestre 6</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="obligatoire" {{ request('type') == 'obligatoire' ? 'selected' : '' }}>Obligatoire</option>
                        <option value="optionnelle" {{ request('type') == 'optionnelle' ? 'selected' : '' }}>Optionnelle</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.matieres.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Total Matières</p>
                            <h3 class="mb-0 fw-bold">{{ $matieres->total() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-book-fill text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Obligatoires</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Matiere::where('type', 'obligatoire')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Optionnelles</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Matiere::where('type', 'optionnelle')->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-star-fill text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Crédits Totaux</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Matiere::sum('credits') }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-award-fill text-info fs-4"></i>
                        </div>
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
                            <th class="px-4 py-3">Code</th>
                            <th class="py-3">Nom de la matière</th>
                            <th class="py-3 text-center">Semestre</th>
                            <th class="py-3 text-center">Crédits</th>
                            <th class="py-3 text-center">Coefficient</th>
                            <th class="py-3">Type</th>
                            <th class="py-3 text-center">Notes</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matieres as $matiere)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary fw-bold">{{ $matiere->code }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold">{{ $matiere->nom }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge {{ $matiere->semestre == 1 ? 'bg-info' : 'bg-purple' }}">
                                        S{{ $matiere->semestre }}
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-success">{{ $matiere->credits }} ECTS</span>
                                </td>
                                <td class="py-3 text-center">
                                    <strong>{{ $matiere->coefficient }}</strong>
                                </td>
                                <td class="py-3">
                                    @if($matiere->type == 'obligatoire')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Obligatoire
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-star me-1"></i>Optionnelle
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <span class="text-muted">{{ $matiere->notes_count }}</span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.matieres.edit', $matiere) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $matiere->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $matiere->id }}" 
                                          action="{{ route('admin.matieres.destroy', $matiere) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Aucune matière trouvée</p>
                                    <a href="{{ route('admin.matieres.create') }}" class="btn btn-primary btn-sm">
                                        Créer la première matière
                                    </a>
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
        {{ $matieres->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette matière ? Cette action supprimera également toutes les notes associées.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush

<style>
    .bg-purple {
        background-color: #9333ea !important;
    }
</style>
@endsection