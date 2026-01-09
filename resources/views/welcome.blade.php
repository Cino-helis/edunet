@extends('layouts.app')

@section('title', 'EduNet - Portail de Gestion Académique')

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

    <!-- Header -->
    <header class="position-relative" style="z-index: 10;">
        <div class="container-fluid px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo et nom (même que dashboard) -->
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-3 p-2 shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" 
                             alt="Logo EduNet" 
                             style="height: 48px; width: 48px; object-fit: contain;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="d-none align-items-center justify-content-center" 
                             style="height: 48px; width: 48px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); border-radius: 8px;">
                            <i class="bi bi-mortarboard-fill text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="text-white">
                        <h5 class="mb-0 fw-bold">EduNet</h5>
                        <small class="opacity-75">Portail Académique</small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    <a href="{{ route('info.help') }}" 
                       class="btn btn-light bg-white bg-opacity-25 text-white border-0 backdrop-blur px-4">
                        <i class="bi bi-question-circle me-2"></i>
                        Help / Support
                    </a>
                    <a href="{{ route('login') }}" 
                       class="btn btn-light px-4 fw-semibold">
                        Se Connecter
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="position-relative" style="z-index: 10;">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    
                    <!-- Titre principal -->
                    <div class="text-center mb-5">
                        <h1 class="display-3 fw-bold text-white mb-3 animate-fade-in">
                            Portail de Gestion Académique
                        </h1>
                        <p class="fs-5 text-white opacity-90 mb-0 animate-fade-in-delay">
                            Accédez à vos résultats, gérez les moyennes et vos statistiques en toute sécurité.
                        </p>
                    </div>

                    <!-- Carte "Accès par Profil" -->
                    <div class="card border-0 shadow-lg rounded-4 mb-5 animate-slide-up" 
                         style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="fw-bold mb-4 text-center">
                                <i class="bi bi-person-badge text-primary me-2"></i>
                                Accès par Profil
                            </h3>

                            <!-- Les 3 espaces d'accès -->
                            <div class="row g-4 mt-2">
                                
                                <!-- Espace Étudiant -->
                                <div class="col-md-4">
                                    <a href="{{ route('login') }}?type=etudiant" class="text-decoration-none">
                                        <div class="card h-100 border-2 border-primary hover-lift transition-all">
                                            <div class="card-body text-center p-4">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                                                    <i class="bi bi-mortarboard-fill text-white" style="font-size: 2.5rem;"></i>
                                                </div>
                                                <h5 class="fw-bold text-primary mb-2">Espace Étudiant</h5>
                                                <p class="text-muted small mb-3">
                                                    Consultez vos notes et vos matières
                                                </p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        Relevés de notes
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-primary bg-opacity-5 border-0 py-3">
                                                <div class="d-flex align-items-center justify-content-center gap-2 text-primary fw-semibold">
                                                    Accéder
                                                    <i class="bi bi-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Espace Enseignant -->
                                <div class="col-md-4">
                                    <a href="{{ route('login') }}?type=enseignant" class="text-decoration-none">
                                        <div class="card h-100 border-2 border-warning hover-lift transition-all">
                                            <div class="card-body text-center p-4">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                                    <i class="bi bi-person-video3 text-white" style="font-size: 2.5rem;"></i>
                                                </div>
                                                <h5 class="fw-bold text-warning mb-2">Espace Enseignant</h5>
                                                <p class="text-muted small mb-3">
                                                    Saisissez les notes et gérez vos matières
                                                </p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        Saisie de notes
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-warning bg-opacity-5 border-0 py-3">
                                                <div class="d-flex align-items-center justify-content-center gap-2 text-warning fw-semibold">
                                                    Accéder
                                                    <i class="bi bi-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <!-- Espace Administrateur -->
                                <div class="col-md-4">
                                    <a href="{{ route('login') }}?type=administrateur" class="text-decoration-none">
                                        <div class="card h-100 border-2 border-danger hover-lift transition-all">
                                            <div class="card-body text-center p-4">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                                                    <i class="bi bi-gear-fill text-white" style="font-size: 2.5rem;"></i>
                                                </div>
                                                <h5 class="fw-bold text-danger mb-2">Espace Administrateur</h5>
                                                <p class="text-muted small mb-3">
                                                    Gérez le système et les statistiques
                                                </p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2">
                                                        <i class="bi bi-shield-check me-1"></i>
                                                        Administration
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-danger bg-opacity-5 border-0 py-3">
                                                <div class="d-flex align-items-center justify-content-center gap-2 text-danger fw-semibold">
                                                    Accéder
                                                    <i class="bi bi-arrow-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Authentification sécurisée -->
                    <div class="text-center mb-4 animate-fade-in-delay-2">
                        <div class="d-inline-flex align-items-center gap-3 px-4 py-3 rounded-pill"
                             style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="bi bi-shield-lock-fill text-white"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold text-success small">Authentification Sécurisée</div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    Protection avancée de vos données
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="position-relative mt-5" style="z-index: 10;">
        <div class="container pb-4">
            <div class="text-center">
                <div class="d-flex flex-wrap justify-content-center gap-3 mb-3">
                    <a href="{{ route('info.mentions-legales') }}" class="text-white text-decoration-none small opacity-75 hover-opacity-100">
                        <i class="bi bi-file-text me-1"></i>
                        Mentions Légales
                    </a>
                    <span class="text-white opacity-50">•</span>
                    <a href="{{ route('info.confidentialite') }}" class="text-white text-decoration-none small opacity-75 hover-opacity-100">
                        <i class="bi bi-shield-check me-1"></i>
                        Politique de Confidentialité (RGPD)
                    </a>
                    <span class="text-white opacity-50">•</span>
                    <a href="{{ route('info.contact') }}" class="text-white text-decoration-none small opacity-75 hover-opacity-100">
                        <i class="bi bi-envelope me-1"></i>
                        Contact Administratif
                    </a>
                </div>
                <p class="text-white opacity-75 small mb-0">
                    © {{ date('Y') }} EduNet - Tous droits réservés
                </p>
            </div>
        </div>
    </footer>
</div>

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
        animation: fadeIn 0.8s ease-out 0.2s both;
    }

    .animate-fade-in-delay-2 {
        animation: fadeIn 0.8s ease-out 0.4s both;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out 0.3s both;
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

    /* Effets hover */
    .hover-lift {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .hover-opacity-100 {
        transition: opacity 0.3s ease;
    }

    .hover-opacity-100:hover {
        opacity: 1 !important;
    }

    .backdrop-blur {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .display-3 {
            font-size: 2.5rem;
        }
        
        .particle {
            display: none;
        }
    }

    /* Transition smooth pour tous les éléments */
    .transition-all {
        transition: all 0.3s ease;
    }

    /* Style pour les cartes au survol */
    .card.border-primary:hover {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .card.border-warning:hover {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    }

    .card.border-danger:hover {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
</style>
@endsection