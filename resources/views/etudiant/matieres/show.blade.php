@extends('layouts.dashboard')

@section('title', 'Détail de la matière')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('etudiant.matieres.index') }}" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="flex-grow-1">
            <h2 class="fw-bold mb-1">{{ $matiere->nom }}</h2>
            <p class="text-muted mb-0">{{ $matiere->code }} - Semestre {{ $matiere->semestre }}</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Imprimer
            </button>
            <a href="{{ route('etudiant.bulletin.index') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-text me-2"></i>Voir le relevé
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Colonne gauche : Informations matière -->
        <div class="col-lg-4">
            <!-- Carte informations -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informations
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Code de la matière</small>
                        <span class="badge bg-primary fs-6 px-3 py-2">{{ $matiere->code }}</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Semestre</small>
                        <span class="badge bg-info fs-6 px-3 py-2">Semestre {{ $matiere->semestre }}</span>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Type</small>
                        @if($matiere->type == 'obligatoire')
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="bi bi-exclamation-circle me-1"></i>Obligatoire
                            </span>
                        @else
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="bi bi-star me-1"></i>Optionnelle
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Crédits ECTS</small>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-award-fill text-primary fs-4"></i>
                            <span class="fw-bold fs-4">{{ $matiere->credits }} ECTS</span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block mb-2">Coefficient</small>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calculator-fill text-primary fs-4"></i>
                            <span class="fw-bold fs-4">{{ $matiere->coefficient }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enseignant -->
            @if($enseignant)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-badge text-primary me-2"></i>Enseignant
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white mb-3" 
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.8rem;">
                                {{ strtoupper(substr($enseignant->prenom, 0, 1)) }}{{ strtoupper(substr($enseignant->nom, 0, 1)) }}
                            </div>
                            <h5 class="fw-bold mb-1">{{ $enseignant->nom_complet }}</h5>
                            @if($enseignant->specialite)
                                <small class="text-muted">{{ $enseignant->specialite }}</small>
                            @endif
                        </div>
                        @if($enseignant->departement)
                            <div class="text-center">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="bi bi-building me-1"></i>{{ $enseignant->departement }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up text-primary me-2"></i>Mes Statistiques
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($stats['moyenne'] !== null)
                        <div class="text-center mb-4 pb-4 border-bottom">
                            <small class="text-muted d-block mb-2">Moyenne</small>
                            <div class="display-4 fw-bold mb-2" style="color: {{ $stats['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                {{ number_format($stats['moyenne'], 2) }}
                            </div>
                            <small class="text-muted">/20</small>
                            <div class="mt-3">
                                <span class="badge px-4 py-2" style="background: {{ $stats['moyenne'] >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $stats['moyenne'] >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 10px; font-size: 0.9rem;">
                                    <i class="bi bi-{{ $stats['moyenne'] >= 10 ? 'check-circle' : 'x-circle' }} me-1"></i>
                                    {{ $stats['statut'] }}
                                </span>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: #f8f9fa;">
                                    <i class="bi bi-trophy-fill text-success fs-3 mb-2 d-block"></i>
                                    <div class="fw-bold fs-4">{{ number_format($stats['meilleure_note'], 2) }}</div>
                                    <small class="text-muted">Meilleure</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: #f8f9fa;">
                                    <i class="bi bi-arrow-down-circle-fill text-danger fs-3 mb-2 d-block"></i>
                                    <div class="fw-bold fs-4">{{ number_format($stats['pire_note'], 2) }}</div>
                                    <small class="text-muted">Plus basse</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Aucune note enregistrée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite : Notes -->
        <div class="col-lg-8">
            <!-- Mes notes -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-journal-text text-primary me-2"></i>Mes Notes
                        </h5>
                        <span class="badge bg-primary px-3 py-2">{{ $stats['nb_notes'] }} note(s)</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="px-4 py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Date</th>
                                        <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Type</th>
                                        <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Note</th>
                                        <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Saisi par</th>
                                        <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notes as $note)
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td class="px-4 py-3" style="border: none;">
                                                <div class="fw-semibold">{{ $note->date_saisie->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $note->date_saisie->diffForHumans() }}</small>
                                            </td>
                                            <td class="py-3 text-center" style="border: none;">
                                                <span class="badge px-2 py-1" style="background: #dbeafe; color: #1e40af; border-radius: 6px;">
                                                    {{ $note->type_evaluation }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-center" style="border: none;">
                                                <div class="d-inline-block">
                                                    <div class="fw-bold" style="font-size: 2rem; color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                                        {{ number_format($note->valeur, 2) }}
                                                    </div>
                                                    <small class="text-muted d-block" style="margin-top: -5px;">/20</small>
                                                </div>
                                            </td>
                                            <td class="py-3" style="border: none;">
                                                <div>{{ $note->enseignant->nom_complet }}</div>
                                            </td>
                                            <td class="py-3 text-center" style="border: none;">
                                                @if($note->valeur >= 16)
                                                    <span class="badge px-3 py-2" style="background: #dcfce7; color: #15803d; border-radius: 8px;">
                                                        <i class="bi bi-trophy me-1"></i>Excellent
                                                    </span>
                                                @elseif($note->valeur >= 14)
                                                    <span class="badge px-3 py-2" style="background: #dbeafe; color: #1e40af; border-radius: 8px;">
                                                        <i class="bi bi-star me-1"></i>Très bien
                                                    </span>
                                                @elseif($note->valeur >= 12)
                                                    <span class="badge px-3 py-2" style="background: #e0e7ff; color: #4f46e5; border-radius: 8px;">
                                                        <i class="bi bi-check-circle me-1"></i>Bien
                                                    </span>
                                                @elseif($note->valeur >= 10)
                                                    <span class="badge px-3 py-2" style="background: #fef3c7; color: #d97706; border-radius: 8px;">
                                                        <i class="bi bi-check me-1"></i>Passable
                                                    </span>
                                                @else
                                                    <span class="badge px-3 py-2" style="background: #fee2e2; color: #dc2626; border-radius: 8px;">
                                                        <i class="bi bi-x-circle me-1"></i>Insuffisant
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if($stats['moyenne'] !== null)
                                    <tfoot style="background: #f8f9fa; border-top: 2px solid #e5e7eb;">
                                        <tr>
                                            <td colspan="2" class="px-4 py-3 text-end fw-bold">MOYENNE :</td>
                                            <td class="py-3 text-center">
                                                <span class="fs-3 fw-bold" style="color: {{ $stats['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                                    {{ number_format($stats['moyenne'], 2) }}
                                                </span>
                                                <small class="text-muted d-block">/20</small>
                                            </td>
                                            <td colspan="2" class="py-3 text-center">
                                                <span class="badge fs-6 px-4 py-2" style="background: {{ $stats['moyenne'] >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $stats['moyenne'] >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 10px;">
                                                    {{ $stats['statut'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Aucune note enregistrée pour cette matière</p>
                            <small class="text-muted">Les notes apparaîtront ici une fois saisies par votre enseignant</small>
                        </div>
                    @endif
                </div>
            </div>

            @if($notes->count() > 0)
                <!-- Évolution des notes -->
                <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-graph-up-arrow text-primary me-2"></i>Évolution de vos notes
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex gap-2 overflow-auto pb-2">
                            @foreach($notes->sortBy('date_saisie') as $index => $note)
                                <div class="text-center p-3 rounded flex-shrink-0" style="background: #f8f9fa; min-width: 100px;">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem;">
                                        Note {{ $index + 1 }}
                                    </small>
                                    <div class="fw-bold fs-4" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                        {{ number_format($note->valeur, 1) }}
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.65rem;">
                                        {{ $note->date_saisie->format('d/m') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($notes->count() >= 2)
                            <div class="mt-3 p-3 rounded" style="background: #f0f9ff;">
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $sortedNotes = $notes->sortBy('date_saisie')->values();
                                        $premiere = $sortedNotes->first()->valeur;
                                        $derniere = $sortedNotes->last()->valeur;
                                        $progression = $derniere - $premiere;
                                    @endphp
                                    <i class="bi bi-{{ $progression >= 0 ? 'arrow-up-circle-fill text-success' : 'arrow-down-circle-fill text-danger' }} fs-4"></i>
                                    <div>
                                        <strong style="color: {{ $progression >= 0 ? '#16a34a' : '#ef4444' }};">
                                            {{ $progression >= 0 ? '+' : '' }}{{ number_format($progression, 2) }} points
                                        </strong>
                                        <small class="text-muted d-block">
                                            Entre votre première et dernière note
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .sidebar, .top-header { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .card { border: 1px solid #000 !important; page-break-inside: avoid; }
    }
</style>
@endsection