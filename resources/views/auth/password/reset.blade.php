@extends('layouts.app')

@section('title', 'EduNet - Nouveau mot de passe')

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
        <!-- Carte principale -->
        <div class="card border-0 shadow-lg rounded-4 glass-effect">
            <div class="card-body p-4 p-md-5">
                <!-- Icône -->
                <div class="text-center mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 96px; height: 96px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="bi bi-shield-lock-fill text-white" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Nouveau mot de passe</h2>
                    <p class="text-muted mb-0">
                        Choisissez un mot de passe sécurisé pour votre compte
                    </p>
                </div>

                <!-- Messages d'erreur -->
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
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Token caché -->
                    <input type="hidden" name="token" value="{{ $token }}">

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
                                value="{{ $email ?? old('email') }}"
                                class="form-control border-start-0 py-3 @error('email') is-invalid @enderror"
                                placeholder="votre.email@universite.fr"
                                required
                                readonly
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium text-secondary">
                            Nouveau mot de passe
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-lock text-secondary"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="form-control border-start-0 border-end-0 py-3 @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                            >
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="password-eye"></i>
                            </span>
                        </div>
                        <small class="text-muted">Minimum 8 caractères</small>
                        @error('password')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-medium text-secondary">
                            Confirmer le mot de passe
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </span>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                class="form-control border-start-0 border-end-0 py-3"
                                placeholder="••••••••"
                                required
                            >
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye" id="password_confirmation-eye"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Indicateur de force du mot de passe -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Force du mot de passe</small>
                            <small id="strength-text" class="fw-medium"></small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Bouton -->
                    <button 
                        type="submit"
                        class="btn btn-primary-custom w-100 py-3 fw-semibold rounded-3 shadow d-flex align-items-center justify-content-center gap-2">
                        Réinitialiser le mot de passe
                        <i class="bi bi-check-circle"></i>
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

        <!-- Conseils sécurité -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 16px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color: #1e40af;">
                    <i class="bi bi-shield-check me-2"></i>Conseils pour un mot de passe sécurisé
                </h6>
                <ul class="mb-0 small" style="color: #1e3a8a;">
                    <li class="mb-1">Au moins 8 caractères</li>
                    <li class="mb-1">Mélangez majuscules et minuscules</li>
                    <li class="mb-1">Incluez des chiffres et symboles</li>
                    <li>Évitez les informations personnelles</li>
                </ul>
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
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(inputId + '-eye');
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('bi-eye');
            eye.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.remove('bi-eye-slash');
            eye.classList.add('bi-eye');
        }
    }

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        const colors = ['#ef4444', '#f59e0b', '#eab308', '#84cc16', '#10b981'];
        const labels = ['Très faible', 'Faible', 'Moyen', 'Bon', 'Excellent'];
        const widths = ['20%', '40%', '60%', '80%', '100%'];

        if (password.length === 0) {
            strengthBar.style.width = '0%';
            strengthText.textContent = '';
        } else {
            strengthBar.style.width = widths[strength - 1];
            strengthBar.style.backgroundColor = colors[strength - 1];
            strengthText.textContent = labels[strength - 1];
            strengthText.style.color = colors[strength - 1];
        }
    });
</script>
@endpush

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endsection