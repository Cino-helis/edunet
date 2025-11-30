@extends('layouts.app')

@section('title', 'EduNet - Accueil')

@section('content')
<div class="min-vh-100 gradient-bg d-flex align-items-center justify-content-center p-4">
    <div class="text-center" style="max-width: 600px;">
        <!-- Logo avec animation -->
        <div class="mb-5 animate-bounce">
            <div class="logo-circle rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                 style="width: 160px; height: 160px;">
                <i class="bi bi-mortarboard-fill text-white" style="font-size: 5rem;"></i>
            </div>
        </div>

        <!-- Titre -->
        <h1 class="display-1 fw-bold text-dark mb-3">
            EduNet
        </h1>

        <!-- Sous-titre -->
        <p class="fs-4 text-secondary mb-5">
            Système de gestion des notes universitaires
        </p>

        <!-- Bouton d'accès -->
        <a href="{{ route('login') }}" 
           class="btn btn-primary-custom btn-lg px-5 py-3 rounded-pill shadow-lg text-white fw-semibold d-inline-flex align-items-center gap-2 mb-4">
            Accéder à l'application
            <i class="bi bi-chevron-right fs-5"></i>
        </a>

        <!-- Message informatif -->
        <div class="card border-0 shadow-lg mt-5 text-start" style="border-radius: 16px; background: rgba(255, 255, 255, 0.95);">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-primary">
                    <i class="bi bi-shield-check me-2"></i>Accès sécurisé
                </h5>
                <p class="mb-3 text-muted">
                    EduNet est un système interne réservé aux membres de l'université.
                </p>
                <div class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <span class="small">Les étudiants reçoivent leurs identifiants lors de leur inscription administrative</span>
                </div>
                <div class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <span class="small">Les enseignants sont enregistrés par le département des ressources humaines</span>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill text-success mt-1"></i>
                    <span class="small">Tous les comptes sont créés et gérés par l'administration</span>
                </div>
                <hr class="my-3">
                <div class="text-center">
                    <small class="text-muted">
                        <i class="bi bi-envelope me-2"></i>Support : support@edunet.com
                    </small>
                </div>
            </div>
        </div>

        <!-- Décoration -->
        <div class="mt-5 d-flex justify-content-center gap-2">
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px;"></div>
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px; animation-delay: 75ms;"></div>
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px; animation-delay: 150ms;"></div>
        </div>
    </div>
</div>
@endsection