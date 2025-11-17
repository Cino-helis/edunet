@extends('layouts.app')

@section('title', 'EduNet - Connexion')

@section('content')
<div class="min-vh-100 gradient-bg-2 d-flex align-items-center justify-content-center p-4 position-relative overflow-hidden">
    <!-- Fond décoratif -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="opacity: 0.2; pointer-events: none;">
        <div class="position-absolute bottom-0 start-0 rounded-circle" 
             style="width: 300px; height: 300px; background: #93c5fd; filter: blur(100px);"></div>
        <div class="position-absolute top-0 end-0 rounded-circle" 
             style="width: 400px; height: 400px; background: #c7d2fe; filter: blur(100px);"></div>
    </div>

    <div class="position-relative w-100" style="max-width: 480px;">
        <!-- Bouton retour -->
        <a href="{{ route('welcome') }}" 
           class="position-absolute text-primary fw-medium text-decoration-none d-flex align-items-center gap-2"
           style="top: -60px; left: 0;">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

        <!-- Carte de connexion -->
        <div class="card border-0 shadow-lg rounded-4 glass-effect">
            <div class="card-body p-4 p-md-5">
                <!-- Logo et titre -->
                <div class="text-center mb-4">
                    <div class="logo-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 96px; height: 96px;">
                        <i class="bi bi-mortarboard-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="fw-bold">EduNet</h2>
                </div>

                <!-- Messages d'erreur -->
                @if ($errors->any())
                    <div class="alert alert-danger border-2 rounded-3 d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading fw-semibold mb-2">Erreurs de connexion</h6>
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Message de succès -->
                @if (session('success'))
                    <div class="alert alert-success border-2 rounded-3 d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                        <p class="mb-0 small">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Formulaire -->
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <!-- Sélection du rôle -->
                    <div class="mb-4">
                        <label class="form-label fw-medium text-secondary mb-3">
                            Sélectionnez votre rôle
                        </label>
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="d-block">
                                    <input type="radio" name="type_utilisateur" value="etudiant" 
                                           class="d-none role-radio" checked>
                                    <div class="card role-card border-2 h-100 active">
                                        <div class="card-body text-center p-3">
                                            <i class="bi bi-person fs-3 text-secondary mb-2"></i>
                                            <div class="small fw-medium text-secondary">Étudiant</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-4">
                                <label class="d-block">
                                    <input type="radio" name="type_utilisateur" value="enseignant" 
                                           class="d-none role-radio">
                                    <div class="card role-card border-2 h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="bi bi-person fs-3 text-secondary mb-2"></i>
                                            <div class="small fw-medium text-secondary">Enseignant</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-4">
                                <label class="d-block">
                                    <input type="radio" name="type_utilisateur" value="administrateur" 
                                           class="d-none role-radio">
                                    <div class="card role-card border-2 h-100">
                                        <div class="card-body text-center p-3">
                                            <i class="bi bi-person fs-3 text-secondary mb-2"></i>
                                            <div class="small fw-medium text-secondary">Admin</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('type_utilisateur')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-medium text-secondary">
                            Adresse email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-envelope text-secondary"></i>
                            </span>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="{{ old('email') }}"
                                class="form-control border-start-0 py-3 @error('email') is-invalid @enderror"
                                placeholder="votre.email@universite.fr"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium text-secondary">
                            Mot de passe
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-lock text-secondary"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="form-control border-start-0 py-3 @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                        @error('password')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-secondary" for="remember">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Bouton de connexion -->
                    <button 
                        type="submit"
                        class="btn btn-primary-custom w-100 py-3 fw-semibold rounded-3 shadow d-flex align-items-center justify-content-center gap-2">
                        Se connecter
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </form>

                <!-- Connexion OTP -->
                <div class="text-center mt-4">
                    <a href="{{ route('login.otp') }}" class="text-primary text-decoration-none fw-medium">
                        Connecter avec OTP
                    </a>
                </div>

                <!-- Liens supplémentaires -->
                <div class="d-flex justify-content-between mt-4 small">
                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none fw-medium">
                        Mot de passe oublié?
                    </a>
                    <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-medium">
                        Créer un compte
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-secondary small mt-4 mb-0">
            © 2024 EduNet - Tous droits réservés
        </p>
    </div>
</div>

@push('scripts')
<script>
    // Gestion de la sélection des rôles
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Retirer la classe active de toutes les cartes
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Ajouter la classe active à la carte sélectionnée
            if (this.checked) {
                this.closest('label').querySelector('.role-card').classList.add('active');
            }
        });
    });
</script>
@endpush
@endsection