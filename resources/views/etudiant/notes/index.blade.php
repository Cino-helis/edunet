@extends('layouts.dashboard')

@section('title', 'Mes Notes')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Mes Notes</h2>
            <p class="text-muted mb-0">Consultez l'historique complet de vos notes</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Imprimer
            </button>
            <a href="#" class="btn btn-primary">
                <i class="bi bi-download me-2"></i>Télécharger PDF
            </a>
        </div>
    </div>

    <!-- Statistiques avec filtres appliqués -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background: #dbeafe;">
                                <i class="bi bi-clipboard-check fs-4" style="color: #0284c7;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Notes</small>
                            <h4 class="mb-0 fw-bold">{{ $statsGlobales['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background: #fef3c7;">
                                <i class="bi bi-star-fill fs-4" style="color: #f59e0b;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Moyenne</small>
                            <h4 class="mb-0 fw-bold">{{ $statsGlobales['moyenne'] }}<small class="fs-6 text-muted">/20</small></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background: #dcfce7;">
                                <i class="bi bi-trophy-fill fs-4" style="color: #16a34a;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Meilleure</small>
                            <h4 class="mb-0 fw-bold text-success">{{ $statsGlobales['meilleure'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                 style="width: 48px; height: 48px; background: #fee2e2;">
                                <i class="bi bi-x-circle-fill fs-4" style="color: #dc2626;"></i>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block">Taux réussite</small>
                            <h4 class="mb-0 fw-bold text-{{ $statsGlobales['taux_reussite'] >= 70 ? 'success' : 'danger' }}">
                                {{ $statsGlobales['taux_reussite'] }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('etudiant.notes.index') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Matière -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Matière</label>
                        <select name="matiere_id" class="form-select" style="border-radius: 10px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Toutes les matières</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type d'évaluation -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Type</label>
                        <select name="type_evaluation" class="form-select" style="border-radius: 10px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tous les types</option>
                            <option value="CC" {{ request('type_evaluation') == 'CC' ? 'selected' : '' }}>CC</option>
                            <option value="TP" {{ request('type_evaluation') == 'TP' ? 'selected' : '' }}>TP</option>
                            <option value="Examen" {{ request('type_evaluation') == 'Examen' ? 'selected' : '' }}>Examen</option>
                            <option value="Projet" {{ request('type_evaluation') == 'Projet' ? 'selected' : '' }}>Projet</option>
                        </select>
                    </div>

                    <!-- Semestre -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Semestre</label>
                        <select name="semestre" class="form-select" style="border-radius: 10px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tous</option>
                            <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>Semestre 1</option>
                            <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>Semestre 2</option>
                        </select>
                    </div>

                    <!-- Période -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Période</label>
                        <select name="periode" class="form-select" style="border-radius: 10px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Toutes</option>
                            <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                            <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                            <option value="semestre" {{ request('periode') == 'semestre' ? 'selected' : '' }}>Ce semestre</option>
                            <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                        </select>
                    </div>

                    <!-- Année académique -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold small">Année</label>
                        <select name="annee_academique" class="form-select" style="border-radius: 10px;" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Toutes</option>
                            @foreach($anneesAcademiques as $annee)
                                <option value="{{ $annee }}" {{ request('annee_academique') == $annee ? 'selected' : '' }}>
                                    {{ $annee }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bouton reset -->
                    <div class="col-md-1 d-flex align-items-end">
                        <a href="{{ route('etudiant.notes.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 10px;" title="Réinitialiser">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>

                <!-- Tri -->
                <div class="row g-2 mt-2">
                    <div class="col-auto">
                        <label class="form-label fw-semibold small mb-1">Trier par :</label>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="sort_by" value="date" id="sort_date" 
                                   {{ request('sort_by', 'date') == 'date' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            <label class="btn btn-outline-primary" for="sort_date">Date</label>

                            <input type="radio" class="btn-check" name="sort_by" value="note" id="sort_note" 
                                   {{ request('sort_by') == 'note' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            <label class="btn btn-outline-primary" for="sort_note">Note</label>

                            <input type="radio" class="btn-check" name="sort_by" value="matiere" id="sort_matiere" 
                                   {{ request('sort_by') == 'matiere' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            <label class="btn btn-outline-primary" for="sort_matiere">Matière</label>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="sort_order" value="desc" id="sort_desc" 
                                   {{ request('sort_order', 'desc') == 'desc' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            <label class="btn btn-outline-secondary" for="sort_desc">
                                <i class="bi bi-arrow-down"></i>
                            </label>

                            <input type="radio" class="btn-check" name="sort_order" value="asc" id="sort_asc" 
                                   {{ request('sort_order') == 'asc' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                            <label class="btn btn-outline-secondary" for="sort_asc">
                                <i class="bi bi-arrow-up"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des notes -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            @if($notes->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="px-4 py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Date</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Matière</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Type</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Note</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Enseignant</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Statut</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes as $note)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="px-4 py-3" style="border: none;">
                                        <div class="fw-semibold">{{ $note->date_saisie->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $note->date_saisie->diffForHumans() }}</small>
                                    </td>
                                    <td class="py-3" style="border: none;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; background: #dbeafe;">
                                                <i class="bi bi-book" style="color: #0284c7;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $note->matiere->nom }}</div>
                                                <small class="text-muted">{{ $note->matiere->code }} - S{{ $note->matiere->semestre }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center" style="border: none;">
                                        <span class="badge px-2 py-1" style="background: #dbeafe; color: #1e40af; border-radius: 6px;">
                                            {{ $note->type_evaluation }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center" style="border: none;">
                                        <div class="d-inline-block">
                                            <div class="fw-bold fs-3" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                                {{ number_format($note->valeur, 2) }}
                                            </div>
                                            <small class="text-muted d-block" style="margin-top: -5px;">/20</small>
                                        </div>
                                    </td>
                                    <td class="py-3" style="border: none;">
                                        <div>
                                            <div class="fw-semibold">{{ $note->enseignant->prenom }} {{ $note->enseignant->nom }}</div>
                                            <small class="text-muted">{{ $note->enseignant->specialite ?? 'Enseignant' }}</small>
                                        </div>
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
                                    <td class="py-3 text-center" style="border: none;">
                                        <a href="{{ route('etudiant.notes.show', $note->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           style="border-radius: 8px;" 
                                           title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Aucune note trouvée avec les filtres sélectionnés</p>
                    <a href="{{ route('etudiant.notes.index') }}" class="btn btn-primary btn-sm">
                        Réinitialiser les filtres
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($notes->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $notes->firstItem() }} à {{ $notes->lastItem() }} sur {{ $notes->total() }} notes
                    </small>
                    {{ $notes->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    @media print {
        .btn, .sidebar, .top-header, .card-footer { display: none !important; }
        .main-content { margin-left: 0 !important; }
        .card { border: 1px solid #000 !important; }
    }
</style>
@endsection