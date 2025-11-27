@extends('layouts.dashboard')

@section('title', 'Gestion des Inscriptions')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Inscriptions</h2>
            <p class="text-muted mb-0">Inscrire les étudiants dans les filières et niveaux</p>
        </div>
        <a href="{{ route('admin.inscriptions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouvelle inscription
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
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
                            <small class="text-muted">Total Inscriptions</small>
                            <h3 class="mb-0 fw-bold">{{ $inscriptions->total() }}</h3>
                        </div>
                        <i class="bi bi-journal-check fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">En cours</small>
                            <h3 class="mb-0 fw-bold text-success">
                                {{ \App\Models\Inscription::where('statut', 'en_cours')->count() }}
                            </h3>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Validées</small>
                            <h3 class="mb-0 fw-bold text-info">
                                {{ \App\Models\Inscription::where('statut', 'validee')->count() }}
                            </h3>
                        </div>
                        <i class="bi bi-check-circle fs-1 text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Suspendues</small>
                            <h3 class="mb-0 fw-bold text-warning">
                                {{ \App\Models\Inscription::where('statut', 'suspendue')->count() }}
                            </h3>
                        </div>
                        <i class="bi bi-pause-circle fs-1 text-warning opacity-25"></i>
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
                            <th class="py-3">Filière</th>
                            <th class="py-3">Niveau</th>
                            <th class="py-3">Année Académique</th>
                            <th class="py-3 text-center">Statut</th>
                            <th class="py-3">Date d'inscription</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inscriptions as $inscription)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-primary fw-bold">
                                                {{ strtoupper(substr($inscription->etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($inscription->etudiant->nom, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $inscription->etudiant->nom_complet }}</div>
                                            <small class="text-muted">{{ $inscription->etudiant->matricule }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">{{ $inscription->filiere->nom }}</td>
                                <td class="py-3">{{ $inscription->niveau->nom }}</td>
                                <td class="py-3">
                                    <span class="badge bg-primary">{{ $inscription->annee_academique }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $statusColors = [
                                            'en_cours' => 'primary',
                                            'validee' => 'success',
                                            'suspendue' => 'warning',
                                            'abandonnee' => 'danger',
                                        ];
                                        $color = $statusColors[$inscription->statut] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ ucfirst(str_replace('_', ' ', $inscription->statut)) }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <small class="text-muted">{{ $inscription->date_inscription->format('d/m/Y') }}</small>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.inscriptions.edit', $inscription) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $inscription->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $inscription->id }}" 
                                          action="{{ route('admin.inscriptions.destroy', $inscription) }}" 
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
                                    <p class="text-muted">Aucune inscription trouvée</p>
                                    <a href="{{ route('admin.inscriptions.create') }}" class="btn btn-primary btn-sm">
                                        Créer la première inscription
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
        {{ $inscriptions->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection