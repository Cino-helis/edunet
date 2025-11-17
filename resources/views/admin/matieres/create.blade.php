@extends('layouts.dashboard')

@section('title', 'Créer une Matière')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.matieres.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Créer une Matière</h2>
                <p class="text-muted mb-0">Ajouter une nouvelle matière au catalogue</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.matieres.store') }}" method="POST">
                    @csrf

                    <!-- Section : Identification -->
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-card-heading text-primary me-2"></i>Identification de la Matière
                    </h5>

                    <div class="row g-3 mb-4">
                        <!-- Code -->
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-semibold">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}"
                                   placeholder="Ex: MATH101"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Code unique de la matière</small>
                        </div>

                        <!-- Nom -->
                        <div class="col-md-8">
                            <label for="nom" class="form-label fw-semibold">
                                Nom de la matière <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   placeholder="Ex: Mathématiques Appliquées"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Section : Paramètres Académiques -->
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-gear text-primary me-2"></i>Paramètres Académiques
                    </h5>

                    <div class="row g-3 mb-4">
                        <!-- Crédits -->
                        <div class="col-md-3">
                            <label for="credits" class="form-label fw-semibold">
                                Crédits ECTS <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('credits') is-invalid @enderror" 
                                       id="credits" 
                                       name="credits" 
                                       value="{{ old('credits', 3) }}"
                                       min="1"
                                       max="20"
                                       required>
                                <span class="input-group-text">ECTS</span>
                            </div>
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Coefficient -->
                        <div class="col-md-3">
                            <label for="coefficient" class="form-label fw-semibold">
                                Coefficient <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('coefficient') is-invalid @enderror" 
                                   id="coefficient" 
                                   name="coefficient" 
                                   value="{{ old('coefficient', 1) }}"
                                   min="0.5"
                                   max="10"
                                   step="0.5"
                                   required>
                            @error('coefficient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div class="col-md-3">
                            <label for="semestre" class="form-label fw-semibold">
                                Semestre <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('semestre') is-invalid @enderror" 
                                    id="semestre" 
                                    name="semestre" 
                                    required>
                                <option value="">Sélectionner</option>
                                <option value="1" {{ old('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                                <option value="2" {{ old('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                                <option value="3" {{ old('semestre') == '3' ? 'selected' : '' }}>Semestre 3</option>
                                <option value="4" {{ old('semestre') == '4' ? 'selected' : '' }}>Semestre 4</option>
                                <option value="5" {{ old('semestre') == '5' ? 'selected' : '' }}>Semestre 5</option>
                                <option value="6" {{ old('semestre') == '6' ? 'selected' : '' }}>Semestre 6</option>
                            </select>
                            @error('semestre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="col-md-3">
                            <label for="type" class="form-label fw-semibold">
                                Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">Sélectionner</option>
                                <option value="obligatoire" {{ old('type') == 'obligatoire' ? 'selected' : '' }}>Obligatoire</option>
                                <option value="optionnelle" {{ old('type') == 'optionnelle' ? 'selected' : '' }}>Optionnelle</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info mb-0">
                                <h6 class="alert-heading">
                                    <i class="bi bi-info-circle me-2"></i>À propos des crédits ECTS
                                </h6>
                                <small>Les crédits ECTS représentent la charge de travail. 
                                    1 crédit = environ 25-30 heures de travail.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning mb-0">
                                <h6 class="alert-heading">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Coefficient
                                </h6>
                                <small>Le coefficient détermine l'importance de la matière 
                                    dans le calcul de la moyenne générale.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Créer la matière
                        </button>
                        <a href="{{ route('admin.matieres.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection