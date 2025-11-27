@extends('layouts.dashboard')

@section('title', 'Nouvelle Affectation')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.affectations.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Nouvelle Affectation</h2>
                <p class="text-muted mb-0">Assigner des matières à des enseignants</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.affectations.store') }}" method="POST">
                    @csrf

                    <!-- Filière -->
                    <div class="mb-4">
                        <label for="filiere_id" class="form-label fw-semibold">
                            Filière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('filiere_id') is-invalid @enderror" 
                                id="filiere_id" name="filiere_id" required>
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Niveau -->
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

                <!-- Enseignants (multiple) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Enseignants <span class="text-danger">*</span>
                    </label>
                    <div class="card">
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <div class="row g-2">
                                @foreach($enseignants as $enseignant)
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="enseignants[]" value="{{ $enseignant->id }}" 
                                                   id="ens-{{ $enseignant->id }}">
                                            <label class="form-check-label w-100" for="ens-{{ $enseignant->id }}">
                                                <div class="fw-semibold">{{ $enseignant->nom_complet }}</div>
                                                <small class="text-muted">{{ $enseignant->specialite ?? 'Aucune spécialité' }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('enseignants')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Matières (multiple) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Matières <span class="text-danger">*</span>
                    </label>
                    <div class="card">
                        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                            <div id="matieres-container">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    <p class="mb-0">Sélectionnez d'abord un niveau</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('matieres')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Année académique -->
                <div class="mb-4">
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

                <!-- Boutons -->
                <div class="d-flex gap-2 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-circle me-2"></i>Créer les affectations
                    </button>
                    <a href="{{ route('admin.affectations.index') }}" class="btn btn-outline-secondary px-4">
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
    // Charger les niveaux
    document.getElementById('filiere_id').addEventListener('change', function() {
        const filiereId = this.value;
        const niveauSelect = document.getElementById('niveau_id');
        const matieresContainer = document.getElementById('matieres-container');
        
        if (!filiereId) {
            niveauSelect.innerHTML = '<option value="">Sélectionner d\'abord une filière</option>';
            niveauSelect.disabled = true;
            matieresContainer.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    <p class="mb-0">Sélectionnez d'abord un niveau</p>
                </div>
            `;
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
    
    // Charger les matières
    document.getElementById('niveau_id').addEventListener('change', function() {
        const niveauId = this.value;
        const filiereId = document.getElementById('filiere_id').value;
        const matieresContainer = document.getElementById('matieres-container');
        
        if (!niveauId) {
            matieresContainer.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    <p class="mb-0">Sélectionnez d'abord un niveau</p>
                </div>
            `;
            return;
        }
        
        fetch(`{{ route('admin.api.matieres-by-niveau') }}?niveau_id=${niveauId}&filiere_id=${filiereId}`)
            .then(response => response.json())
            .then(matieres => {
                if (matieres.length === 0) {
                    matieresContainer.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            <p class="mb-0">Aucune matière affectée à ce niveau</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="row g-2">';
                matieres.forEach(matiere => {
                    html += `
                        <div class="col-md-6">
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="checkbox" 
                                       name="matieres[]" value="${matiere.id}" 
                                       id="mat-${matiere.id}">
                                <label class="form-check-label w-100" for="mat-${matiere.id}">
                                    <div class="fw-semibold">${matiere.nom}</div>
                                    <small class="text-muted">${matiere.code} - ${matiere.credits} ECTS</small>
                                </label>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                matieresContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des matières');
            });
    });
</script>
@endpush
@endsection