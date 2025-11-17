@extends('layouts.dashboard')

@section('title', 'Modifier une Note')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Modifier une Note</h2>
                <p class="text-muted mb-0">Mettre à jour les informations de la note</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.notes.update', $note) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Info de la note actuelle -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading fw-bold">
                            <i class="bi bi-info-circle me-2"></i>Note actuelle
                        </h6>
                        <div class="row g-2 mt-2">
                            <div class="col-md-6">
                                <strong>Étudiant :</strong> {{ $note->etudiant->nom_complet }}
                            </div>
                            <div class="col-md-6">
                                <strong>Matière :</strong> {{ $note->matiere->nom }}
                            </div>
                            <div class="col-md-6">
                                <strong>Note :</strong> 
                                <span class="badge bg-{{ $note->valeur >= 10 ? 'success' : 'danger' }} fs-6">
                                    {{ number_format($note->valeur, 2) }}/20
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Date :</strong> {{ $note->date_saisie->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Étudiant -->
                    <div class="mb-4">
                        <label for="etudiant_id" class="form-label fw-semibold">
                            Étudiant <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('etudiant_id') is-invalid @enderror" 
                                id="etudiant_id" name="etudiant_id" required>
                            @foreach($etudiants as $etudiant)
                                <option value="{{ $etudiant->id }}" 
                                    {{ old('etudiant_id', $note->etudiant_id) == $etudiant->id ? 'selected' : '' }}>
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
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" 
                                        {{ old('matiere_id', $note->matiere_id) == $matiere->id ? 'selected' : '' }}>
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
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" 
                                        {{ old('enseignant_id', $note->enseignant_id) == $enseignant->id ? 'selected' : '' }}>
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
                                       value="{{ old('valeur', $note->valeur) }}"
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
                                <option value="CC" {{ old('type_evaluation', $note->type_evaluation) == 'CC' ? 'selected' : '' }}>CC</option>
                                <option value="TP" {{ old('type_evaluation', $note->type_evaluation) == 'TP' ? 'selected' : '' }}>TP</option>
                                <option value="Examen" {{ old('type_evaluation', $note->type_evaluation) == 'Examen' ? 'selected' : '' }}>Examen</option>
                                <option value="Projet" {{ old('type_evaluation', $note->type_evaluation) == 'Projet' ? 'selected' : '' }}>Projet</option>
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
                                   value="{{ old('annee_academique', $note->annee_academique) }}"
                                   required>
                            @error('annee_academique')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
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