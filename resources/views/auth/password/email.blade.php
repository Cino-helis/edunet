@extends('layouts.app')

@section('title', 'EduNet - Réinitialiser le mot de passe')

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
        <a href="{{ route('login') }}" 
           class="position-absolute text-primary fw-medium text-decoration-none d-flex align-items-center gap-2"
           style="top: -60px; left: 0;">
            <i class="bi bi-arrow-left"></i>
            Retour à la connexion
        </a>

        <!-- Carte principale -->
        <div class="card border-0 shadow-lg rounded-4 glass-effect">
            <div class="card-body p-4 p-md-5">
                <!-- Icône -->
                <div class="text-center mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 96px; height: 96px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                        <i class="bi bi-key-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Mot de passe oublié ?</h2>
                    <p class="text-muted mb-0">
                        Pas de problème. Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                    </p>
                </div>

                <!-- Messages -->
                @if (session('success'))
                    <div class="alert alert-success border-2 rounded-3 d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                        <div>
                            <h6 class="alert-heading fw-semibold mb-1">Email envoyé !</h6>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-2 rounded-3 d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="alert-heading fw-semibold mb-2">Erreur</h6>
                            <ul class="mb-0 small ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Formulaire -->
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

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

                    <!-- Bouton -->
                    <button 
                        type="submit"
                        class="btn btn-primary-custom w-100 py-3 fw-semibold rounded-3 shadow d-flex align-items-center justify-content-center gap-2">
                        Envoyer le lien de réinitialisation
                        <i class="bi bi-send"></i>
                    </button>
                </form>

                <!-- Lien retour -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-medium">
                        <i class="bi bi-arrow-left me-2"></i>Retour à la connexion
                    </a>
                </div>
            </div>
        </div>

        <!-- Info supplémentaire -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                         style="width: 48px; height: 48px; background: white;">
                        <i class="bi bi-info-circle text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-2" style="color: #92400e;">Besoin d'aide ?</h6>
                        <p class="mb-0 small" style="color: #78350f;">
                            Si vous ne recevez pas l'email dans les 5 minutes, vérifiez vos spams ou contactez l'administration.
                            <br><br>
                            <strong>Support :</strong> support@edunet.com | +228 XX XX XX XX
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-secondary small mt-4 mb-0">
            © 2024 EduNet - Tous droits réservés
        </p>
    </div>
</div>
@endsection