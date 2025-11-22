@extends('layouts.dashboard')

@section('title', 'Gestion des Niveaux')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestion des Niveaux</h2>
            <p class="text-muted mb-0">Liste de tous les niveaux par filière</p>
        </div>
        <a href="{{ route('admin.niveaux.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Ajouter un niveau
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Code</th>
                            <th class="py-3">Nom</th>
                            <th class="py-3">Filière</th>
                            <th class="py-3 text-center">Ordre</th>
                            <th class="py-3 text-center">Crédits requis</th>
                            <th class="py-3 text-center">Matières</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($niveaux as $niveau)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-primary">{{ $niveau->code }}</span>
                                </td>
                                <td class="py-3 fw-semibold">{{ $niveau->nom }}</td>
                                <td class="py-3">{{ $niveau->filiere->nom }}</td>
                                <td class="py-3 text-center">{{ $niveau->ordre }}</td>
                                <td class="py-3 text-center">{{ $niveau->credits_requis }}</td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('admin.niveaux.matieres', $niveau) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-book"></i> Gérer ({{ $niveau->matieres->count() }})
                                    </a>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.niveaux.edit', $niveau) }}" 
                                           class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete({{ $niveau->id }})" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <form id="delete-form-{{ $niveau->id }}" 
                                          action="{{ route('admin.niveaux.destroy', $niveau) }}" 
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
                                    <p class="text-muted">Aucun niveau trouvé</p>
                                    <a href="{{ route('admin.niveaux.create') }}" class="btn btn-primary btn-sm">
                                        Créer le premier niveau
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $niveaux->links() }}
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
@endsection