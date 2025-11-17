@extends('layouts.dashboard')

@section('title', 'Gestion des Filières')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Filières</h2>
            <p class="text-muted mb-0">Liste de toutes les filières</p>
        </div>
        <a href="{{ route('admin.filieres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajouter une filière
        </a>
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

    <!-- Tableau -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Code</th>
                            <th class="py-3">Nom</th>
                            <th class="py-3">Durée</th>
                            <th class="py-3">Niveaux</th>
                            <th class="py-3">Étudiants</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filieres as $filiere)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary">{{ $filiere->code }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold">{{ $filiere->nom }}</div>
                                    <small class="text-muted">{{ Str::limit($filiere->description, 50) }}</small>
                                </td>
                                <td class="py-3">{{ $filiere->duree_annees }} ans</td>
                                <td class="py-3">
                                    <span class="badge bg-info">{{ $filiere->niveaux_count }} niveaux</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-success">{{ $filiere->inscriptions_count }} étudiants</span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.filieres.show', $filiere) }}" 
                                           class="btn btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.filieres.edit', $filiere) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $filiere->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $filiere->id }}" 
                                          action="{{ route('admin.filieres.destroy', $filiere) }}" 
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
                                    <p class="text-muted">Aucune filière trouvée</p>
                                    <a href="{{ route('admin.filieres.create') }}" class="btn btn-primary btn-sm">
                                        Créer la première filière
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
        {{ $filieres->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette filière ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection