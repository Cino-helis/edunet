@extends('layouts.dashboard')

@section('title', 'Créer un Étudiant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Créer un Étudiant</h2>
                <p class="text-muted mb-0">Ajouter un nouvel étudiant au système</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.etudiants.store') }}" method="POST">
                    @csrf

                    <!-- Section : Informations du Compte -->
                    <h5 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-person-badge text-primary me-2"></i>Informations du Compte
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
                                       placeholder="etudiant@example.com"
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
                        <i class="bi bi-person text-primary me-2"></i>Informations Personnelles
                    </h5>

                    <div class="row g-3 mb-4">
                        <!-- Matricule -->
                        <div class="col-md-4">
                            <label for="matricule" class="form-label fw-semibold">
                                Matricule <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('matricule') is-invalid @enderror" 
                                   id="matricule" 
                                   name="matricule" 
                                   value="{{ old('matricule') }}"
                                   placeholder="ETU2024001"
                                   required>
                            @error('matricule')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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

                        <!-- Date de naissance -->
                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label fw-semibold">
                                Date de naissance <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('date_naissance') is-invalid @enderror" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="{{ old('date_naissance') }}"
                                   required>
                            @error('date_naissance')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lieu de naissance -->
                        <div class="col-md-6">
                            <label for="lieu_naissance" class="form-label fw-semibold">
                                Lieu de naissance <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                   id="lieu_naissance" 
                                   name="lieu_naissance" 
                                   value="{{ old('lieu_naissance') }}"
                                   placeholder="Paris"
                                   required>
                            @error('lieu_naissance')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Créer l'étudiant
                        </button>
                        <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info box -->
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note :</strong> L'étudiant recevra ses identifiants de connexion par email. 
            Il pourra se connecter avec l'email et le mot de passe que vous avez définis.
        </div>
    </div>
</div>
@endsection