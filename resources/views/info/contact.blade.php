@extends('layouts.app')

@section('title', 'Contact - EduNet')

@section('content')
<div class="min-vh-100 bg-light">
    <!-- Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-envelope me-3"></i>Contactez-nous
            </h1>
            <p class="lead mb-0">Nous sommes à votre écoute</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            
            <!-- Formulaire de contact -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Envoyez-nous un message</h3>

                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('info.contact.send') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nom" class="form-label fw-semibold">
                                        Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="sujet" class="form-label fw-semibold">
                                        Sujet <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('sujet') is-invalid @enderror" 
                                           id="sujet" 
                                           name="sujet" 
                                           value="{{ old('sujet') }}"
                                           placeholder="Ex: Question sur mon inscription"
                                           required>
                                    @error('sujet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">
                                        Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="6"
                                              placeholder="Décrivez votre demande..."
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maximum 2000 caractères</small>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="col-lg-4">
                <!-- Coordonnées -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Nos coordonnées</h5>
                        
                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Adresse</h6>
                                <p class="text-muted small mb-0">
                                    123 Avenue de l'Université<br>
                                    Lomé, Togo
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="bi bi-telephone-fill text-success fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Téléphone</h6>
                                <p class="text-muted small mb-0">
                                    +228 XX XX XX XX<br>
                                    <small>Lun - Ven : 8h - 17h</small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px;">
                                    <i class="bi bi-envelope-fill text-info fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted small mb-0">
                                    support@edunet.com<br>
                                    admin@edunet.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horaires -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Horaires d'ouverture</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Lundi - Vendredi</span>
                            <span class="fw-semibold">8h - 17h</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Samedi</span>
                            <span class="fw-semibold">9h - 13h</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Dimanche</span>
                            <span class="text-danger fw-semibold">Fermé</span>
                        </div>

                        <hr class="my-3">

                        <div class="alert alert-info mb-0 small">
                            <i class="bi bi-info-circle me-2"></i>
                            Nous répondons à tous les messages dans un délai de 24h ouvrées.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection