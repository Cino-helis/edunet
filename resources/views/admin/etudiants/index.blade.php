@extends('layouts.dashboard')

@section('title', 'Gestion des Étudiants')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Étudiants</h2>
            <p class="text-muted mb-0">Liste de tous les étudiants inscrits</p>
        </div>
        <a href="{{ route('admin.etudiants.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajouter un étudiant
        </a>
    </div>

    <!-- Filtres et Recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.etudiants.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Rechercher par nom, prénom, matricule..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="filiere" class="form-select">
                        <option value="">Toutes les filières</option>
                        <!-- À remplir dynamiquement -->
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Rechercher
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary">
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
                            <p class="text-muted mb-1 small">Total Étudiants</p>
                            <h3 class="mb-0 fw-bold">{{ $etudiants->total() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
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
                            <p class="text-muted mb-1 small">Nouveaux (ce mois)</p>
                            <h3 class="mb-0 fw-bold">{{ \App\Models\Etudiant::whereMonth('created_at', now()->month)->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-plus-fill text-success fs-4"></i>
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
                            <th class="px-4 py-3">Matricule</th>
                            <th class="py-3">Nom Complet</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Date de naissance</th>
                            <th class="py-3">Lieu de naissance</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etudiants as $etudiant)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary">{{ $etudiant->matricule }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-primary fw-bold">{{ strtoupper(substr($etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($etudiant->nom, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $etudiant->nom_complet }}</div>
                                            <small class="text-muted">Étudiant</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">{{ $etudiant->user->email }}</td>
                                <td class="py-3">{{ $etudiant->date_naissance->format('d/m/Y') }}</td>
                                <td class="py-3">{{ $etudiant->lieu_naissance }}</td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.etudiants.show', $etudiant) }}" 
                                           class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.etudiants.edit', $etudiant) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $etudiant->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $etudiant->id }}" 
                                          action="{{ route('admin.etudiants.destroy', $etudiant) }}" 
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
                                    <p class="text-muted">Aucun étudiant trouvé</p>
                                    <a href="{{ route('admin.etudiants.create') }}" class="btn btn-primary btn-sm">
                                        Ajouter le premier étudiant
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
        {{ $etudiants->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action supprimera également son compte utilisateur et toutes ses données associées.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection