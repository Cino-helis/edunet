@extends('layouts.app')

@section('title', 'EduNet - Connexion')

@section('content')
<div class="min-vh-100 position-relative overflow-hidden">
    <!-- Background Image avec overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" 
         style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.9) 0%, rgba(147, 197, 253, 0.85) 50%, rgba(191, 219, 254, 0.9) 100%), 
                url('{{ asset('images/backgrounds/default-bg.png') }}') center/cover no-repeat;
                z-index: -1;">
    </div>

    <!-- Particules décoratives -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0; pointer-events: none;">
        <div class="particle" style="top: 10%; left: 10%;"></div>
        <div class="particle" style="top: 20%; right: 15%;"></div>
        <div class="particle" style="bottom: 15%; left: 20%;"></div>
        <div class="particle" style="bottom: 25%; right: 10%;"></div>
        <div class="particle" style="top: 50%; left: 50%;"></div>
    </div>

    <!-- Header simplifié -->
    <header class="position-relative" style="z-index: 10;">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo avec chapeau (même style que dashboard) -->
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 56px; height: 56px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                        <i class="bi bi-mortarboard-fill text-white" style="font-size: 2rem;"></i>
                    </div>
                    <div class="text-white">
                        <h5 class="mb-0 fw-bold">EduNet</h5>
                        <small class="opacity-75">Gestion des notes</small>
                    </div>
                </div>

                <!-- Bouton retour -->
                <a href="{{ route('welcome') }}" class="btn btn-light px-4">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="position-relative" style="z-index: 10;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    
                    <!-- Titre -->
                    <div class="text-center mb-4 animate-fade-in">
                        <h2 class="fw-bold text-white mb-2">Connexion à votre compte</h2>
                        <p class="text-white opacity-90 mb-0">Accédez à votre espace personnel</p>
                    </div>

                    <!-- Carte de connexion -->
                    <div class="card border-0 shadow-lg rounded-4 animate-slide-up" 
                         style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                        <div class="card-body p-4 p-md-5">
                            
                            <!-- Logo centré avec chapeau (même style que dashboard) -->
                            <div class="text-center mb-4">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm"
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                                    <i class="bi bi-mortarboard-fill text-white" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>

                            <!-- Messages d'erreur -->
                            @if ($errors->any())
                                <div class="alert alert-danger border-0 rounded-3 mb-4" style="background: #fee2e2; color: #dc2626;">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">Erreur de connexion</h6>
                                            <ul class="mb-0 small ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Message de succès -->
                            @if (session('success'))
                                <div class="alert alert-success border-0 rounded-3 mb-4" style="background: #dcfce7; color: #16a34a;">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                        <p class="mb-0 small">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Formulaire -->
                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf

                                <!-- Sélection du rôle -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-secondary mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Sélectionnez votre rôle
                                    </label>
                                    <div class="row g-2">
                                        <!-- Étudiant -->
                                        <div class="col-4">
                                            <label class="d-block">
                                                <input type="radio" name="type_utilisateur" value="etudiant" 
                                                       class="d-none role-radio" 
                                                       {{ (request('type') === 'etudiant' || !request('type')) ? 'checked' : '' }}>
                                                <div class="card role-card border-2 h-100 cursor-pointer {{ (request('type') === 'etudiant' || !request('type')) ? 'active border-primary' : '' }}"
                                                     style="transition: all 0.3s;">
                                                    <div class="card-body text-center p-3">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                             style="width: 40px; height: 40px; background: #dbeafe;">
                                                            <i class="bi bi-mortarboard-fill fs-5" style="color: #3b82f6;"></i>
                                                        </div>
                                                        <div class="small fw-semibold">Étudiant</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Enseignant -->
                                        <div class="col-4">
                                            <label class="d-block">
                                                <input type="radio" name="type_utilisateur" value="enseignant" 
                                                       class="d-none role-radio"
                                                       {{ request('type') === 'enseignant' ? 'checked' : '' }}>
                                                <div class="card role-card border-2 h-100 cursor-pointer {{ request('type') === 'enseignant' ? 'active border-warning' : '' }}"
                                                     style="transition: all 0.3s;">
                                                    <div class="card-body text-center p-3">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                             style="width: 40px; height: 40px; background: #fef3c7;">
                                                            <i class="bi bi-person-video3 fs-5" style="color: #f59e0b;"></i>
                                                        </div>
                                                        <div class="small fw-semibold">Enseignant</div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <!-- Administrateur -->
                                        <div class="col-4">
                                            <label class="d-block">
                                                <input type="radio" name="type_utilisateur" value="administrateur" 
                                                       class="d-none role-radio"
                                                       {{ request('type') === 'administrateur' ? 'checked' : '' }}>
                                                <div class="card role-card border-2 h-100 cursor-pointer {{ request('type') === 'administrateur' ? 'active border-danger' : '' }}"
                                                     style="transition: all 0.3s;">
                                                    <div class="card-body text-center p-3">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                             style="width: 40px; height: 40px; background: #fee2e2;">
                                                            <i class="bi bi-gear-fill fs-5" style="color: #ef4444;"></i>
                                                        </div>
                                                        <div class="small fw-semibold">Admin</div>
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
                                    <label for="email" class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-envelope me-2"></i>Adresse email
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email"
                                        name="email" 
                                        value="{{ old('email') }}"
                                        class="form-control py-3 @error('email') is-invalid @enderror"
                                        style="border-radius: 10px;"
                                        placeholder="votre.email@universite.fr"
                                        required
                                        autofocus
                                    >
                                    @error('email')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mot de passe avec toggle -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold text-secondary">
                                        <i class="bi bi-lock me-2"></i>Mot de passe
                                    </label>
                                    <div class="position-relative">
                                        <input 
                                            type="password" 
                                            id="password"
                                            name="password" 
                                            class="form-control py-3 pe-5 @error('password') is-invalid @enderror"
                                            style="border-radius: 10px;"
                                            placeholder="••••••••"
                                            required
                                        >
                                        <button 
                                            type="button" 
                                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-secondary"
                                            id="togglePassword"
                                            style="text-decoration: none; padding: 0.75rem 1rem; z-index: 10;"
                                            tabindex="-1"
                                        >
                                            <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Se souvenir de moi & Mot de passe oublié -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label text-secondary small" for="remember">
                                            Se souvenir de moi
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small fw-medium">
                                        Mot de passe oublié ?
                                    </a>
                                </div>

                                <!-- Bouton de connexion -->
                                <button 
                                    type="submit"
                                    class="btn btn-primary w-100 py-3 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2"
                                    style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border: none;">
                                    Se connecter
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Message informatif -->
                    <div class="card border-0 shadow-sm mt-4 animate-fade-in-delay" 
                         style="border-radius: 16px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="bi bi-shield-lock-fill text-white fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-2" style="color: #059669;">
                                        <i class="bi bi-info-circle me-1"></i>Nouveaux utilisateurs ?
                                    </h6>
                                    <p class="mb-0 small text-secondary">
                                        Vos identifiants de connexion vous sont communiqués par l'administration 
                                        après votre inscription. Contactez le secrétariat pour obtenir vos accès.
                                        <br><br>
                                        <strong>📧 secretariat@edunet.com</strong> | 
                                        <strong>📞 +228 XX XX XX XX</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-white opacity-75 small mt-4 mb-0">
                        © {{ date('Y') }} EduNet - Tous droits réservés
                    </p>

                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    // Toggle Password Visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        // Toggle le type de l'input
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle l'icône
        if (type === 'password') {
            eyeIcon.classList.remove('bi-eye-slash-fill');
            eyeIcon.classList.add('bi-eye-fill');
        } else {
            eyeIcon.classList.remove('bi-eye-fill');
            eyeIcon.classList.add('bi-eye-slash-fill');
        }
    });

    // Gestion de la sélection des rôles
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            // Retirer la classe active de toutes les cartes
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active', 'border-primary', 'border-warning', 'border-danger');
                card.style.transform = 'scale(1)';
            });
            
            // Ajouter la classe active à la carte sélectionnée
            if (this.checked) {
                const card = this.closest('label').querySelector('.role-card');
                card.classList.add('active');
                card.style.transform = 'scale(1.05)';
                
                // Ajouter la couleur selon le rôle
                const role = this.value;
                if (role === 'etudiant') {
                    card.classList.add('border-primary');
                } else if (role === 'enseignant') {
                    card.classList.add('border-warning');
                } else if (role === 'administrateur') {
                    card.classList.add('border-danger');
                }
            }
        });
    });

    // Animation au survol
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-4px)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
</script>
@endpush

<style>
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.5); opacity: 0.3; }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .animate-fade-in-delay {
        animation: fadeIn 0.8s ease-out 0.4s both;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out 0.2s both;
    }

    /* Particules */
    .particle {
        position: absolute;
        width: 8px;
        height: 8px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        animation: pulse 4s ease-in-out infinite;
    }

    .particle:nth-child(2) { animation-delay: 0.5s; }
    .particle:nth-child(3) { animation-delay: 1s; }
    .particle:nth-child(4) { animation-delay: 1.5s; }
    .particle:nth-child(5) { animation-delay: 2s; }

    /* Cursor pointer */
    .cursor-pointer {
        cursor: pointer;
    }

    /* Style des cartes de rôles */
    .role-card {
        transition: all 0.3s ease;
    }

    .role-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .role-card.active {
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.2);
    }

    /* Style du bouton toggle password */
    #togglePassword {
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    #togglePassword:hover {
        color: #3b82f6 !important;
    }

    #togglePassword:focus {
        outline: none;
        box-shadow: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .particle {
            display: none;
        }
    }
</style>
@endsection