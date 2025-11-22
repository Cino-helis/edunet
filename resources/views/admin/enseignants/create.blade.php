@extends('layouts.dashboard')

@section('title', 'Créer un Enseignant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.enseignants.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Créer un Enseignant</h2>
                <p class="text-muted mb-0">Ajouter un nouvel enseignant au système</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.enseignants.store') }}" method="POST">
                    @csrf

                    <!-- Section : Informations du Compte -->
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-badge text-success me-2"></i>Informations du Compte
                    </h5>

                    <div class="row g-3 mb-4">
                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="enseignant@example.com"
                                       required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">
                                Mot de passe <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 caractères</small>
                        </div>
                    </div>

                    <!-- Section : Informations Personnelles -->
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-person text-success me-2"></i>Informations Personnelles
                    </h5>

                    <div class="row g-3 mb-4">
                        <!-- Nom -->
                        <div class="col-md-6">
                            <label for="nom" class="form-label fw-semibold">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   placeholder="DUPONT"
                                   required>
                            @error('nom')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div class="col-md-6">
                            <label for="prenom" class="form-label fw-semibold">
                                Prénom <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('prenom') is-invalid @enderror" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="{{ old('prenom') }}"
                                   placeholder="Jean"
                                   required>
                            @error('prenom')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Spécialité -->
                        <div class="col-md-6">
                            <label for="specialite" class="form-label fw-semibold">
                                Spécialité
                            </label>
                            <input type="text" 
                                   class="form-control @error('specialite') is-invalid @enderror" 
                                   id="specialite" 
                                   name="specialite" 
                                   value="{{ old('specialite') }}"
                                   placeholder="Ex: Mathématiques, Informatique...">
                            @error('specialite')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Département -->
                        <div class="col-md-6">
                            <label for="departement" class="form-label fw-semibold">
                                Département
                            </label>
                            <input type="text" 
                                   class="form-control @error('departement') is-invalid @enderror" 
                                   id="departement" 
                                   name="departement" 
                                   value="{{ old('departement') }}"
                                   placeholder="Ex: Sciences, Lettres...">
                            @error('departement')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-2"></i>Créer l'enseignant
                        </button>
                        <a href="{{ route('admin.enseignants.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info box -->
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note :</strong> L'enseignant recevra ses identifiants de connexion par email. 
            Il pourra se connecter avec l'email et le mot de passe que vous avez définis.
        </div>
    </div>
</div>
@endsection