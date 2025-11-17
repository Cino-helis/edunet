@extends('layouts.dashboard')

@section('title', 'Statistiques Avancées')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Statistiques Avancées</h2>
            <p class="text-muted mb-0">Vue d'ensemble complète du système</p>
        </div>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimer
        </button>
    </div>

    <!-- Statistiques Générales -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Total Étudiants</small>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($stats['total_etudiants']) }}</h3>
                        </div>
                        <i class="bi bi-people-fill fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Total Enseignants</small>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['total_enseignants'] }}</h3>
                        </div>
                        <i class="bi bi-person-badge-fill fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Moyenne Générale</small>
                            <h3 class="mb-0 fw-bold text-warning">{{ $stats['moyenne_generale'] }}/20</h3>
                        </div>
                        <i class="bi bi-star-fill fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <small class="text-muted">Taux de Réussite</small>
                            <h3 class="mb-0 fw-bold text-info">{{ $stats['taux_reussite'] }}%</h3>
                        </div>
                        <i class="bi bi-trophy-fill fs-1 text-info opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Répartition des notes -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-pie-chart text-primary me-2"></i>Répartition des Notes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartRepartition" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 Matières -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-trophy text-warning me-2"></i>Top 5 Meilleures Matières
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($topMatieres as $matiere)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">{{ $matiere->nom }}</div>
                                <small class="text-muted">{{ $matiere->code }}</small>
                            </div>
                            <span class="badge bg-success fs-6">
                                {{ number_format($matiere->notes_avg_valeur, 2) }}/20
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Étudiants -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-star-fill text-warning me-2"></i>Top 10 Meilleurs Étudiants
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Rang</th>
                            <th class="py-3">Matricule</th>
                            <th class="py-3">Nom Complet</th>
                            <th class="py-3 text-center">Moyenne</th>
                            <th class="py-3 text-center">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topEtudiants as $index => $etudiant)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }} fs-6">
                                        #{{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-primary">{{ $etudiant->matricule }}</span>
                                </td>
                                <td class="py-3 fw-semibold">{{ $etudiant->nom_complet }}</td>
                                <td class="py-3 text-center">
                                    <span class="fs-5 fw-bold text-success">
                                        {{ number_format($etudiant->notes_avg_valeur, 2) }}/20
                                    </span>
                                </td>
                                <td class="py-3 text-center">{{ $etudiant->notes->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notes par Type d'Évaluation -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-clipboard-data text-primary me-2"></i>Statistiques par Type d'Évaluation
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($notesParType as $type)
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h6 class="fw-bold">{{ $type->type_evaluation }}</h6>
                                <div class="fs-4 fw-bold text-primary">
                                    {{ number_format($type->moyenne, 2) }}/20
                                </div>
                                <small class="text-muted">{{ $type->total }} notes</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique répartition des notes
    const ctx = document.getElementById('chartRepartition');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Excellent (16-20)', 'Très Bien (14-16)', 'Bien (12-14)', 'Assez Bien (10-12)', 'Passable (8-10)', 'Insuffisant (<8)'],
            datasets: [{
                data: [
                    {{ $repartitionNotes['excellent'] }},
                    {{ $repartitionNotes['tres_bien'] }},
                    {{ $repartitionNotes['bien'] }},
                    {{ $repartitionNotes['assez_bien'] }},
                    {{ $repartitionNotes['passable'] }},
                    {{ $repartitionNotes['insuffisant'] }}
                ],
                backgroundColor: [
                    '#10b981',
                    '#3b82f6',
                    '#8b5cf6',
                    '#f59e0b',
                    '#ef4444',
                    '#6b7280'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection