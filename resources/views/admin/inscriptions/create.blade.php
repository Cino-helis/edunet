@extends('layouts.dashboard')

@section('title', 'Nouvelle Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Nouvelle Inscription</h2>
                <p class="text-muted mb-0">Inscrire un ou plusieurs étudiants dans une filière</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.inscriptions.store') }}" method="POST">
                    @csrf

                    <!-- Sélection de la filière -->
                    <div class="mb-4">
                        <label for="filiere_id" class="form-label fw-semibold">
                            Filière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('filiere_id') is-invalid @enderror" 
                                id="filiere_id" name="filiere_id" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sélection du niveau -->
                    <div class="mb-4">
                        <label for="niveau_id" class="form-label fw-semibold">
                            Niveau <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('niveau_id') is-invalid @enderror" 
                                id="niveau_id" name="niveau_id" required disabled>
                            <option value="">Sélectionner d'abord une filière</option>
                        </select>
                        @error('niveau_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sélection des étudiants (multiple) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Étudiants à inscrire <span class="text-danger">*</span>
                        </label>
                        <div class="card">
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="search-etudiant" 
                                           placeholder="Rechercher un étudiant...">
                                </div>
                                
                                @if($etudiants->isEmpty())
                                    <div class="text-center py-4">
                                        <i class="bi bi-inbox fs-3 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">Tous les étudiants sont déjà inscrits</p>
                                    </div>
                                @else
                                    <div class="row g-2" id="etudiants-list">
                                        @foreach($etudiants as $etudiant)
                                            <div class="col-md-6 etudiant-item" data-name="{{ strtolower($etudiant->nom_complet) }}" data-matricule="{{ strtolower($etudiant->matricule) }}">
                                                <div class="form-check p-3 border rounded">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="etudiants[]" value="{{ $etudiant->id }}" 
                                                           id="etudiant-{{ $etudiant->id }}">
                                                    <label class="form-check-label w-100" for="etudiant-{{ $etudiant->id }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 35px; height: 35px;">
                                                                <span class="text-primary fw-bold small">
                                                                    {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($etudiant->nom, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $etudiant->nom_complet }}</div>
                                                                <small class="text-muted">{{ $etudiant->matricule }}</small>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('etudiants')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Année et Statut -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="annee_academique" class="form-label fw-semibold">
                                Année Académique <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('annee_academique') is-invalid @enderror" 
                                   id="annee_academique" 
                                   name="annee_academique" 
                                   value="{{ old('annee_academique', '2024-2025') }}"
                                   required>
                            @error('annee_academique')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="statut" class="form-label fw-semibold">
                                Statut <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" name="statut" required>
                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="validee" {{ old('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                                <option value="suspendue" {{ old('statut') == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                                <option value="abandonnee" {{ old('statut') == 'abandonnee' ? 'selected' : '' }}>Abandonnée</option>
                            </select>
                            @error('statut')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Créer les inscriptions
                        </button>
                        <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Charger les niveaux selon la filière
    document.getElementById('filiere_id').addEventListener('change', function() {
        const filiereId = this.value;
        const niveauSelect = document.getElementById('niveau_id');
        
        if (!filiereId) {
            niveauSelect.innerHTML = '<option value="">Sélectionner d\'abord une filière</option>';
            niveauSelect.disabled = true;
            return;
        }
        
        fetch(`{{ route('admin.api.niveaux-by-filiere') }}?filiere_id=${filiereId}`)
            .then(response => response.json())
            .then(niveaux => {
                let options = '<option value="">Sélectionner un niveau</option>';
                niveaux.forEach(niveau => {
                    options += `<option value="${niveau.id}">${niveau.nom}</option>`;
                });
                niveauSelect.innerHTML = options;
                niveauSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des niveaux');
            });
    });
    
    // Recherche d'étudiants
    document.getElementById('search-etudiant').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const etudiantItems = document.querySelectorAll('.etudiant-item');
        
        etudiantItems.forEach(item => {
            const name = item.dataset.name;
            const matricule = item.dataset.matricule;
            
            if (name.includes(searchTerm) || matricule.includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection