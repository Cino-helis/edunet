@extends('layouts.dashboard')

@section('title', 'Gestion des Affectations')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Affectations</h2>
            <p class="text-muted mb-0">Assigner des enseignants aux filières, niveaux et matières</p>
        </div>
        <a href="{{ route('admin.affectations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouvelle affectation
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

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Total Affectations</small>
                            <h3 class="mb-0 fw-bold">{{ $affectations->total() }}</h3>
                        </div>
                        <i class="bi bi-diagram-3-fill fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Enseignants affectés</small>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ \App\Models\Affectation::distinct('enseignant_id')->count() }}
                            </h3>
                        </div>
                        <i class="bi bi-person-badge fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Année en cours</small>
                            <h3 class="mb-0 fw-bold text-info">2024-2025</h3>
                        </div>
                        <i class="bi bi-calendar-check fs-1 text-info opacity-25"></i>
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
                            <th class="px-4 py-3">Enseignant</th>
                            <th class="py-3">Filière</th>
                            <th class="py-3">Niveau</th>
                            <th class="py-3">Matière</th>
                            <th class="py-3">Année Académique</th>
                            <th class="py-3">Date d'affectation</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($affectations as $affectation)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-success fw-bold">
                                                {{ strtoupper(substr($affectation->enseignant->prenom, 0, 1)) }}{{ strtoupper(substr($affectation->enseignant->nom, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $affectation->enseignant->nom_complet }}</div>
                                            <small class="text-muted">{{ $affectation->enseignant->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">{{ $affectation->filiere->nom }}</td>
                                <td class="py-3">{{ $affectation->niveau->nom }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold">{{ $affectation->matiere->nom }}</div>
                                    <small class="text-muted">{{ $affectation->matiere->code }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-primary">{{ $affectation->annee_academique }}</span>
                                </td>
                                <td class="py-3">
                                    <small class="text-muted">{{ $affectation->date_affectation->format('d/m/Y') }}</small>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete({{ $affectation->id }})" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    
                                    <form id="delete-form-{{ $affectation->id }}" 
                                          action="{{ route('admin.affectations.destroy', $affectation) }}" 
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
                                    <p class="text-muted">Aucune affectation trouvée</p>
                                    <a href="{{ route('admin.affectations.create') }}" class="btn btn-primary btn-sm">
                                        Créer la première affectation
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
        {{ $affectations->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection