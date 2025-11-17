@extends('layouts.dashboard')

@section('title', 'Mon Profil')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Mon Profil</h2>
        <p class="text-muted mb-0">Gérer vos informations personnelles et vos préférences</p>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Erreur :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Colonne gauche : Carte de profil -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <!-- Avatar -->
                    <div class="position-relative d-inline-block mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 120px;">
                            @if(isset($user->avatar))
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" 
                                     class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <span class="text-primary fw-bold" style="font-size: 3rem;">
                                    {{ strtoupper(substr($profil->prenom, 0, 1)) }}{{ strtoupper(substr($profil->nom, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" 
                                style="width: 36px; height: 36px;"
                                data-bs-toggle="modal" 
                                data-bs-target="#avatarModal">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                    </div>

                    <h4 class="fw-bold mb-1">{{ $profil->nom_complet }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <span class="badge bg-{{ $user->type_utilisateur == 'administrateur' ? 'danger' : ($user->type_utilisateur == 'enseignant' ? 'warning' : 'success') }} px-3 py-2">
                        <i class="bi bi-person-badge me-1"></i>
                        {{ ucfirst($user->type_utilisateur) }}
                    </span>

                    @if($user->type_utilisateur === 'etudiant')
                        <div class="mt-3">
                            <span class="badge bg-primary px-3 py-2">
                                {{ $profil->matricule }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-light">
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <small class="text-muted d-block">Membre depuis</small>
                            <strong>{{ $user->created_at->format('M Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Dernière connexion</small>
                            <strong>{{ $user->derniere_connexion ? $user->derniere_connexion->diffForHumans() : 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides (pour étudiants) -->
            @if($user->type_utilisateur === 'etudiant')
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-graph-up text-primary me-2"></i>Mes Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Notes enregistrées</span>
                            <strong class="text-primary">{{ $profil->notes->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Moyenne générale</span>
                            <strong class="text-{{ $profil->notes->avg('valeur') >= 10 ? 'success' : 'danger' }}">
                                {{ $profil->notes->count() > 0 ? number_format($profil->notes->avg('valeur'), 2) : 'N/A' }}/20
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Inscriptions actives</span>
                            <strong class="text-info">{{ $profil->inscriptions->where('statut', 'en_cours')->count() }}</strong>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Colonne droite : Formulaires -->
        <div class="col-lg-8">
            <!-- Informations personnelles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person text-primary me-2"></i>Informations Personnelles
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profil.update-info') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Email -->
                            <div class="col-md-12">
                                <label for="email" class="form-label fw-semibold">
                                    Adresse Email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}"
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nom et Prénom -->
                            <div class="col-md-6">
                                <label for="nom" class="form-label fw-semibold">Nom</label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom', $profil->nom) }}"
                                       required>
                                @error('nom')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="prenom" class="form-label fw-semibold">Prénom</label>
                                <input type="text" 
                                       class="form-control @error('prenom') is-invalid @enderror" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="{{ old('prenom', $profil->prenom) }}"
                                       required>
                                @error('prenom')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Champs spécifiques aux étudiants -->
                            @if($user->type_utilisateur === 'etudiant')
                                <div class="col-md-6">
                                    <label for="date_naissance" class="form-label fw-semibold">Date de naissance</label>
                                    <input type="date" 
                                           class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" 
                                           name="date_naissance" 
                                           value="{{ old('date_naissance', $profil->date_naissance->format('Y-m-d')) }}"
                                           required>
                                    @error('date_naissance')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="lieu_naissance" class="form-label fw-semibold">Lieu de naissance</label>
                                    <input type="text" 
                                           class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" 
                                           name="lieu_naissance" 
                                           value="{{ old('lieu_naissance', $profil->lieu_naissance) }}"
                                           required>
                                    @error('lieu_naissance')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Champs spécifiques aux enseignants -->
                            @if($user->type_utilisateur === 'enseignant')
                                <div class="col-md-6">
                                    <label for="specialite" class="form-label fw-semibold">Spécialité</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="specialite" 
                                           name="specialite" 
                                           value="{{ old('specialite', $profil->specialite) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="departement" class="form-label fw-semibold">Département</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="departement" 
                                           name="departement" 
                                           value="{{ old('departement', $profil->departement) }}">
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Changer le mot de passe -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock text-primary me-2"></i>Sécurité du Compte
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profil.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Mot de passe actuel -->
                            <div class="col-md-12">
                                <label for="current_password" class="form-label fw-semibold">
                                    Mot de passe actuel
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    Nouveau mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 8 caractères</small>
                            </div>

                            <!-- Confirmer le mot de passe -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    Confirmer le mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="bi bi-shield-check me-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Avatar -->
<div class="position-relative d-inline-block mb-3">
    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" 
         style="width: 120px; height: 120px;">
        @if($user->avatar && Storage::disk('public')->exists($user->avatar))
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" 
                 class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
        @else
            <span class="text-primary fw-bold" style="font-size: 3rem;">
                {{ strtoupper(substr($profil->prenom, 0, 1)) }}{{ strtoupper(substr($profil->nom, 0, 1)) }}
            </span>
        @endif
    </div>
    <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" 
            style="width: 36px; height: 36px;"
            data-bs-toggle="modal" 
            data-bs-target="#avatarModal">
        <i class="bi bi-camera-fill"></i>
    </button>
</div>

@push('scripts')
<script>
    // Prévisualisation de l'avatar
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">
                `;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection