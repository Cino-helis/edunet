@extends('layouts.dashboard')

@section('title', 'Ajouter une Note')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('enseignant.notes.index') }}" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Ajouter une Note</h2>
                <p class="text-muted mb-0">Enregistrer une note pour un étudiant</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('enseignant.notes.store') }}" method="POST">
                    @csrf

                    <!-- Filière -->
                    <div class="mb-4">
                        <label for="filiere_id" class="form-label fw-semibold">
                            Filière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="filiere_id" required style="border-radius: 10px;">
                            <option value="">Sélectionner une filière</option>
                            @foreach($affectations->groupBy('filiere_id') as $filiereId => $affectationsByFiliere)
                                <option value="{{ $filiereId }}">
                                    {{ $affectationsByFiliere->first()->filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Niveau -->
                    <div class="mb-4">
                        <label for="niveau_id" class="form-label fw-semibold">
                            Niveau <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="niveau_id" required style="border-radius: 10px;" disabled>
                            <option value="">Sélectionner d'abord une filière</option>
                        </select>
                    </div>

                    <!-- Matière -->
                    <div class="mb-4">
                        <label for="matiere_id" class="form-label fw-semibold">
                            Matière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('matiere_id') is-invalid @enderror" 
                                id="matiere_id" name="matiere_id" required style="border-radius: 10px;" disabled>
                            <option value="">Sélectionner d'abord un niveau</option>
                        </select>
                        @error('matiere_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sélection de l'étudiant -->
                    <div class="mb-4">
                        <label for="etudiant_id" class="form-label fw-semibold">
                            Étudiant <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('etudiant_id') is-invalid @enderror" 
                                id="etudiant_id" name="etudiant_id" required style="border-radius: 10px;" disabled>
                            <option value="">Sélectionner d'abord une matière</option>
                        </select>
                        @error('etudiant_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Note et Type -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="valeur" class="form-label fw-semibold">
                                Note <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('valeur') is-invalid @enderror" 
                                       id="valeur" 
                                       name="valeur" 
                                       value="{{ old('valeur') }}"
                                       min="0"
                                       max="20"
                                       step="0.25"
                                       placeholder="0.00"
                                       required
                                       style="border-radius: 10px 0 0 10px;">
                                <span class="input-group-text" style="border-radius: 0 10px 10px 0;">/20</span>
                            </div>
                            @error('valeur')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="type_evaluation" class="form-label fw-semibold">
                                Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('type_evaluation') is-invalid @enderror" 
                                    id="type_evaluation" name="type_evaluation" required style="border-radius: 10px;">
                                <option value="">Sélectionner</option>
                                <option value="CC" {{ old('type_evaluation') == 'CC' ? 'selected' : '' }}>CC</option>
                                <option value="TP" {{ old('type_evaluation') == 'TP' ? 'selected' : '' }}>TP</option>
                                <option value="Examen" {{ old('type_evaluation') == 'Examen' ? 'selected' : '' }}>Examen</option>
                                <option value="Projet" {{ old('type_evaluation') == 'Projet' ? 'selected' : '' }}>Projet</option>
                            </select>
                            @error('type_evaluation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="annee_academique" class="form-label fw-semibold">
                                Année Académique <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('annee_academique') is-invalid @enderror" 
                                   id="annee_academique" 
                                   name="annee_academique" 
                                   value="{{ old('annee_academique', '2024-2025') }}"
                                   required
                                   style="border-radius: 10px;">
                            @error('annee_academique')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer la note
                        </button>
                        <a href="{{ route('enseignant.notes.index') }}" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info box -->
        <div class="alert alert-info mt-3 border-0" style="border-radius: 12px; background: #e0f2fe; color: #0369a1;">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note :</strong> La note sera immédiatement visible par l'étudiant après validation.
        </div>
    </div>
</div>

@push('scripts')
<script>
// Données des affectations depuis le serveur
const affectations = @json($affectations);

// Charger les niveaux selon la filière sélectionnée
document.getElementById('filiere_id').addEventListener('change', function() {
    const filiereId = this.value;
    const niveauSelect = document.getElementById('niveau_id');
    const matiereSelect = document.getElementById('matiere_id');
    const etudiantSelect = document.getElementById('etudiant_id');
    
    // Réinitialiser
    niveauSelect.innerHTML = '<option value="">Sélectionner un niveau</option>';
    matiereSelect.innerHTML = '<option value="">Sélectionner d\'abord un niveau</option>';
    etudiantSelect.innerHTML = '<option value="">Sélectionner d\'abord une matière</option>';
    niveauSelect.disabled = false;
    matiereSelect.disabled = true;
    etudiantSelect.disabled = true;
    
    if (!filiereId) {
        niveauSelect.disabled = true;
        return;
    }
    
    // Filtrer les affectations
    const affectationsByFiliere = affectations.filter(aff => aff.filiere_id == filiereId);
    
    // Niveaux uniques
    const niveauxUniques = {};
    affectationsByFiliere.forEach(aff => {
        if (!niveauxUniques[aff.niveau_id]) {
            niveauxUniques[aff.niveau_id] = aff.niveau;
        }
    });
    
    // Remplir le select
    Object.values(niveauxUniques).forEach(niveau => {
        const option = document.createElement('option');
        option.value = niveau.id;
        option.textContent = niveau.nom;
        niveauSelect.appendChild(option);
    });
});

// Charger les matières selon le niveau
document.getElementById('niveau_id').addEventListener('change', function() {
    const filiereId = document.getElementById('filiere_id').value;
    const niveauId = this.value;
    const matiereSelect = document.getElementById('matiere_id');
    const etudiantSelect = document.getElementById('etudiant_id');
    
    matiereSelect.innerHTML = '<option value="">Sélectionner une matière</option>';
    etudiantSelect.innerHTML = '<option value="">Sélectionner d\'abord une matière</option>';
    matiereSelect.disabled = false;
    etudiantSelect.disabled = true;
    
    if (!niveauId) {
        matiereSelect.disabled = true;
        return;
    }
    
    // Filtrer les affectations
    const affectationsByNiveau = affectations.filter(aff => 
        aff.filiere_id == filiereId && aff.niveau_id == niveauId
    );
    
    // Remplir le select
    affectationsByNiveau.forEach(aff => {
        const option = document.createElement('option');
        option.value = aff.matiere_id;
        option.textContent = aff.matiere.nom + ' (' + aff.matiere.code + ')';
        matiereSelect.appendChild(option);
    });
});

// Charger les étudiants selon la matière sélectionnée
document.getElementById('matiere_id').addEventListener('change', function() {
    const niveauId = document.getElementById('niveau_id').value;
    const matiereId = this.value;
    const etudiantSelect = document.getElementById('etudiant_id');
    if (!matiereId) {
    etudiantSelect.innerHTML = '<option value="">Sélectionner d\'abord une matière</option>';
    etudiantSelect.disabled = true;
    return;
}

// Charger les étudiants via AJAX
fetch(`{{ route('enseignant.api.etudiants-by-niveau') }}?niveau_id=${niveauId}`)
    .then(response => response.json())
    .then(etudiants => {
        let options = '<option value="">Sélectionner un étudiant</option>';
        etudiants.forEach(etudiant => {
            options += `<option value="${etudiant.id}">${etudiant.matricule} - ${etudiant.prenom} ${etudiant.nom}</option>`;
        });
        etudiantSelect.innerHTML = options;
        etudiantSelect.disabled = false;
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement des étudiants');
    });
});
</script>
@endpush
@endsection