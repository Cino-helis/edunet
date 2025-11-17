@extends('layouts.dashboard')

@section('title', 'Ajouter une Note')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Ajouter une Note</h2>
                <p class="text-muted mb-0">Enregistrer une nouvelle note pour un étudiant</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.notes.store') }}" method="POST">
                    @csrf

                    <!-- Étudiant -->
                    <div class="mb-4">
                        <label for="etudiant_id" class="form-label fw-semibold">
                            Étudiant <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('etudiant_id') is-invalid @enderror" 
                                id="etudiant_id" name="etudiant_id" required>
                            <option value="">Sélectionner un étudiant</option>
                            @foreach($etudiants as $etudiant)
                                <option value="{{ $etudiant->id }}" {{ old('etudiant_id') == $etudiant->id ? 'selected' : '' }}>
                                    {{ $etudiant->matricule }} - {{ $etudiant->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                        @error('etudiant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Matière et Enseignant -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="matiere_id" class="form-label fw-semibold">
                                Matière <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('matiere_id') is-invalid @enderror" 
                                    id="matiere_id" name="matiere_id" required>
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->code }} - {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="enseignant_id" class="form-label fw-semibold">
                                Enseignant <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('enseignant_id') is-invalid @enderror" 
                                    id="enseignant_id" name="enseignant_id" required>
                                <option value="">Sélectionner un enseignant</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->nom_complet }}
                                    </option>
                                @endforeach
                            </select>
                            @error('enseignant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
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
                                       step="0.01"
                                       required>
                                <span class="input-group-text">/20</span>
                            </div>
                            @error('valeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="type_evaluation" class="form-label fw-semibold">
                                Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('type_evaluation') is-invalid @enderror" 
                                    id="type_evaluation" name="type_evaluation" required>
                                <option value="">Sélectionner</option>
                                <option value="CC" {{ old('type_evaluation') == 'CC' ? 'selected' : '' }}>CC (Contrôle Continu)</option>
                                <option value="TP" {{ old('type_evaluation') == 'TP' ? 'selected' : '' }}>TP (Travaux Pratiques)</option>
                                <option value="Examen" {{ old('type_evaluation') == 'Examen' ? 'selected' : '' }}>Examen</option>
                                <option value="Projet" {{ old('type_evaluation') == 'Projet' ? 'selected' : '' }}>Projet</option>
                            </select>
                            @error('type_evaluation')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                                   placeholder="2024-2025"
                                   required>
                            @error('annee_academique')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer la note
                        </button>
                        <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection