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

                    <!-- Sélection de la classe -->
                    <div class="mb-4">
                        <label for="affectation_id" class="form-label fw-semibold">
                            Classe <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="affectation_id" required style="border-radius: 10px;">
                            <option value="">Sélectionner une classe</option>
                            @foreach($affectations as $affectation)
                                <option value="{{ $affectation->id }}" 
                                        data-matiere="{{ $affectation->matiere_id }}"
                                        data-niveau="{{ $affectation->niveau_id }}">
                                    {{ $affectation->matiere->nom }} - {{ $affectation->niveau->filiere->nom }} {{ $affectation->niveau->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection de l'étudiant -->
                    <div class="mb-4">
                        <label for="etudiant_id" class="form-label fw-semibold">
                            Étudiant <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('etudiant_id') is-invalid @enderror" 
                                id="etudiant_id" name="etudiant_id" required style="border-radius: 10px;" disabled>
                            <option value="">Sélectionner d'abord une classe</option>
                        </select>
                        @error('etudiant_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Matière (hidden, auto-rempli) -->
                    <input type="hidden" name="matiere_id" id="matiere_id">

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
    // Charger les étudiants selon la classe sélectionnée
    document.getElementById('affectation_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const niveauId = selectedOption.getAttribute('data-niveau');
        const matiereId = selectedOption.getAttribute('data-matiere');
        
        if (!niveauId) {
            document.getElementById('etudiant_id').innerHTML = '<option value="">Sélectionner d\'abord une classe</option>';
            document.getElementById('etudiant_id').disabled = true;
            return;
        }
        
        // Remplir le champ matiere_id
        document.getElementById('matiere_id').value = matiereId;
        
        // Charger les étudiants
        fetch(`{{ route('enseignant.api.etudiants-by-niveau') }}?niveau_id=${niveauId}`)
            .then(response => response.json())
            .then(etudiants => {
                let options = '<option value="">Sélectionner un étudiant</option>';
                etudiants.forEach(etudiant => {
                    options += `<option value="${etudiant.id}">${etudiant.matricule} - ${etudiant.prenom} ${etudiant.nom}</option>`;
                });
                document.getElementById('etudiant_id').innerHTML = options;
                document.getElementById('etudiant_id').disabled = false;
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des étudiants');
            });
    });
</script>
@endpush
@endsection