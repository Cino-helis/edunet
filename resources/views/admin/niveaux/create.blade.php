@extends('layouts.dashboard')

@section('title', 'Créer un Niveau')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.niveaux.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Créer un Niveau</h2>
                <p class="text-muted mb-0">Ajouter un nouveau niveau à une filière</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.niveaux.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-12">
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="code" class="form-label fw-semibold">
                                Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code') }}"
                                   placeholder="Ex: L1, L2, M1"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="ordre" class="form-label fw-semibold">
                                Ordre <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('ordre') is-invalid @enderror" 
                                   id="ordre" 
                                   name="ordre" 
                                   value="{{ old('ordre', 1) }}"
                                   min="1"
                                   required>
                            @error('ordre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="credits_requis" class="form-label fw-semibold">
                                Crédits requis <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('credits_requis') is-invalid @enderror" 
                                   id="credits_requis" 
                                   name="credits_requis" 
                                   value="{{ old('credits_requis', 60) }}"
                                   min="0"
                                   required>
                            @error('credits_requis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="nom" class="form-label fw-semibold">
                                Nom du niveau <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   placeholder="Ex: Licence 1, Master 1"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Créer le niveau
                        </button>
                        <a href="{{ route('admin.niveaux.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection