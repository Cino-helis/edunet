@extends('layouts.dashboard')

@section('title', 'Détail de la note')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('etudiant.notes.index') }}" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-1">Détail de la note</h2>
            <p class="text-muted mb-0">{{ $note->matiere->nom }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Informations de la note -->
        <div class="col-lg-8">
            <!-- Carte principale -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-5 text-center">
                    <!-- Note -->
                    <div class="mb-4">
                        <div class="d-inline-block rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; background: {{ $note->valeur >= 10 ? '#dcfce7' : '#fee2e2' }};">
                            <div>
                                <div class="fw-bold" style="font-size: 4rem; color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                    {{ number_format($note->valeur, 2) }}
                                </div>
                                <div class="text-muted" style="margin-top: -10px;">/20</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    @if($note->valeur >= 16)
                        <h4 class="fw-bold mb-2" style="color: #16a34a;">
                            <i class="bi bi-trophy-fill me-2"></i>Excellent !
                        </h4>
                        <p class="text-muted">Vous avez obtenu une excellente note</p>
                    @elseif($note->valeur >= 14)
                        <h4 class="fw-bold mb-2" style="color: #0284c7;">
                            <i class="bi bi-star-fill me-