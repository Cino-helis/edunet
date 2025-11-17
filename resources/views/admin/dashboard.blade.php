@extends('layouts.dashboard')

@section('title', 'EduNet - Dashboard Administrateur')

@section('content')
<div>
    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-2">Tableau de bord administrateur</h2>
        <p class="text-muted mb-0">Vue d'ensemble du système de gestion des notes</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-label">Étudiants</div>
                        <div class="stat-value">{{ number_format($stats['etudiants']['total']) }}</div>
                        <div class="stat-change {{ $stats['etudiants']['variation'] >= 0 ? 'positive' : 'text-danger' }}">
                            <i class="bi bi-arrow-{{ $stats['etudiants']['variation'] >= 0 ? 'up' : 'down' }}"></i> 
                            {{ $stats['etudiants']['label'] }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-label">Enseignants</div>
                        <div class="stat-value">{{ $stats['enseignants']['total'] }}</div>
                        <div class="stat-change positive">
                            <i class="bi bi-arrow-up"></i> {{ $stats['enseignants']['label'] }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-label">Moyenne générale</div>
                        <div class="stat-value">{{ $stats['moyenne']['valeur'] }}<small class="fs-6 text-muted">/20</small></div>
                        <div class="stat-change {{ $stats['moyenne']['variation'] >= 0 ? 'positive' : 'text-danger' }}">
                            <i class="bi bi-arrow-{{ $stats['moyenne']['variation'] >= 0 ? 'up' : 'down' }}"></i> 
                            {{ $stats['moyenne']['label'] }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="flex-grow-1">
                        <div class="stat-label">Taux de réussite</div>
                        <div class="stat-value">{{ $stats['reussite']['taux'] }}<small class="fs-6 text-muted">%</small></div>
                        <div class="stat-change {{ $stats['reussite']['variation'] >= 0 ? 'positive' : 'text-danger' }}">
                            <i class="bi bi-arrow-{{ $stats['reussite']['variation'] >= 0 ? 'up' : 'down' }}"></i> 
                            {{ $stats['reussite']['label'] }}
                        </div>
                    </div>
                    <div class="stat-icon" style="background: #f3e8ff; color: #9333ea;">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Performance par filière -->
        <div class="col-lg-8">
            <div class="performance-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="fw-bold mb-0">Performance par filière</h5>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download"></i> Exporter
                    </button>
                </div>

                @forelse($performanceParFiliere as $filiere)
                    <div class="performance-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ $filiere['nom'] }}</h6>
                                <small class="text-muted">{{ $filiere['nb_etudiants'] }} étudiants</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">{{ $filiere['moyenne'] }}/20</div>
                                <small style="color: {{ $filiere['couleur'] }}">{{ $filiere['taux_reussite'] }}%</small>
                            </div>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom" style="width: {{ $filiere['taux_reussite'] }}%; background: {{ $filiere['couleur'] }};"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                        <p>Aucune donnée disponible pour le moment</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Activités récentes -->
        <div class="col-lg-4">
            <div class="activity-card">
                <h5 class="fw-bold mb-4">Activités récentes</h5>

                @forelse($activitesRecentes as $activite)
                    <div class="activity-item">
                        <div class="activity-dot {{ $activite['couleur'] }}"></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">{{ $activite['titre'] }}</div>
                            <small class="text-muted d-block">{{ $activite['auteur'] }} • {{ $activite['date_relative'] }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-clock-history fs-3 mb-2 d-block"></i>
                        <small>Aucune activité récente</small>
                    </div>
                @endforelse

                <div class="text-center mt-3">
                    <a href="#" class="text-primary text-decoration-none fw-medium">
                        Voir tout l'historique
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
<div class="mt-4">
    <h5 class="fw-bold mb-3">Actions rapides</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.filieres.create') }}" class="action-btn primary">
                <i class="bi bi-plus-circle-fill"></i>
                Ajouter une filière
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.etudiants.create') }}" class="action-btn secondary">
                <i class="bi bi-person-plus-fill"></i>
                Créer un étudiant
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.matieres.create') }}" class="action-btn secondary">
                <i class="bi bi-plus-circle-fill"></i>
                Créer une matière
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.statistiques') }}" class="action-btn secondary">
                <i class="bi bi-bar-chart-fill"></i>
                Statistiques
            </a>
        </div>
    </div>
</div>
</div>
@endsection