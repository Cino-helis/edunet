@extends('layouts.app')

@section('title', 'EduNet - Accueil')

@section('content')
<div class="min-vh-100 gradient-bg d-flex align-items-center justify-content-center p-4">
    <div class="text-center">
        <!-- Logo avec animation -->
        <div class="mb-5 animate-bounce">
            <div class="logo-circle rounded-circle d-flex align-items-center justify-center mx-auto" 
                 style="width: 160px; height: 160px;">
                <i class="bi bi-mortarboard-fill text-white" style="font-size: 5rem;"></i>
            </div>
        </div>

        <!-- Titre -->
        <h1 class="display-1 fw-bold text-dark mb-3">
            EduNet
        </h1>

        <!-- Sous-titre -->
        <p class="fs-4 text-secondary mb-5" style="max-width: 500px; margin: 0 auto;">
            Système de gestion des notes universitaires
        </p>

        <!-- Bouton d'accès -->
        <a href="{{ route('login') }}" 
           class="btn btn-primary-custom btn-lg px-5 py-3 rounded-pill shadow-lg text-white fw-semibold d-inline-flex align-items-center gap-2">
            Accéder à l'application
            <i class="bi bi-chevron-right fs-5"></i>
        </a>

        <!-- Décoration -->
        <div class="mt-5 d-flex justify-content-center gap-2">
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px;"></div>
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px; animation-delay: 75ms;"></div>
            <div class="bg-primary rounded-circle pulse" style="width: 12px; height: 12px; animation-delay: 150ms;"></div>
        </div>
    </div>
</div>
@endsection