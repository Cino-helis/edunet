@extends('layouts.dashboard')

@section('title', 'Modifier une Filière')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Modifier la Filière</h2>
                <p class="text-muted mb-0">{{ $filiere->nom }}</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Code -->
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-semibold">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $filiere->code) }}"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Durée -->
                        <div class="col-md-4">
                            <label for="duree_annees" class="form-label fw-semibold">
                                Durée (années) <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('duree_annees') is-invalid @enderror" 
                                   id="duree_annees" 
                                   name="duree_annees" 
                                   value="{{ old('duree_annees', $filiere->duree_annees) }}"
                                   min="1"
                                   max="10"
                                   required>
                            @error('duree_annees')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div class="col-12">
                            <label for="nom" class="form-label fw-semibold">
                                Nom de la filière <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $filiere->nom) }}"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">
                                Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4">{{ old('description', $filiere->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection