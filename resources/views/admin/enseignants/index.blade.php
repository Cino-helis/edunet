@extends('layouts.dashboard')

@section('title', 'Gestion des Enseignants')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Enseignants</h2>
            <p class="text-muted mb-0">Liste de tous les enseignants</p>
        </div>
        <a href="{{ route('admin.enseignants.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajouter un enseignant
        </a>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.enseignants.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Rechercher par nom, prénom, email..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" 
                           name="specialite" 
                           class="form-control" 
                           placeholder="Spécialité"
                           value="{{ request('specialite') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Rechercher
                    </button>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('admin.enseignants.index') }}" class="btn btn-outline-secondary w-100">
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
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Total Enseignants</p>
                            <h3 class="mb-0 fw-bold">{{ $enseignants->total() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-badge-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Nouveaux (ce mois)</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Enseignant::whereMonth('created_at', now()->month)->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-plus-fill text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1 small">Notes saisies</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Note::count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-journal-text text-warning fs-4"></i>
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
                            <th class="px-4 py-3">Nom Complet</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Spécialité</th>
                            <th class="py-3">Département</th>
                            <th class="py-3 text-center">Notes saisies</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enseignants as $enseignant)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-success fw-bold">{{ strtoupper(substr($enseignant->prenom, 0, 1)) }}{{ strtoupper(substr($enseignant->nom, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $enseignant->nom_complet }}</div>
                                            <small class="text-muted">Enseignant</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">{{ $enseignant->user->email }}</td>
                                <td class="py-3">
                                    @if($enseignant->specialite)
                                        <span class="badge bg-info">{{ $enseignant->specialite }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3">{{ $enseignant->departement ?? '-' }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-primary">{{ $enseignant->notes->count() }}</span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.enseignants.show', $enseignant) }}" 
                                           class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.enseignants.edit', $enseignant) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $enseignant->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $enseignant->id }}" 
                                          action="{{ route('admin.enseignants.destroy', $enseignant) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Aucun enseignant trouvé</p>
                                    <a href="{{ route('admin.enseignants.create') }}" class="btn btn-primary btn-sm">
                                        Ajouter le premier enseignant
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
        {{ $enseignants->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ? Cette action supprimera également son compte utilisateur et toutes ses données associées.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection