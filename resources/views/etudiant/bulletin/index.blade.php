@extends('layouts.dashboard')

@section('title', 'Mon Bulletin')

@section('content')
<div>
    <!-- Header avec actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Mon Bulletin de Notes</h2>
            <p class="text-muted mb-0">Relevé de notes et résultats académiques</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline-primary" onclick="window.print()" style="border-radius: 10px 0 0 10px;">
                <i class="bi bi-printer me-2"></i>Imprimer
            </button>
            <a href="#" class="btn btn-primary" style="border-radius: 0 10px 10px 0;">
                <i class="bi bi-download me-2"></i>Télécharger PDF
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('etudiant.bulletin.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Année académique</label>
                    <select name="annee_academique" class="form-select" style="border-radius: 10px;" onchange="this.form.submit()">
                        @foreach($anneesAcademiques as $annee)
                            <option value="{{ $annee }}" {{ $anneeSelectionnee == $annee ? 'selected' : '' }}>
                                {{ $annee }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Semestre</label>
                    <select name="semestre" class="form-select" style="border-radius: 10px;" onchange="this.form.submit()">
                        <option value="">Toute l'année</option>
                        <option value="1" {{ $semestreSelectionne == '1' ? 'selected' : '' }}>Semestre 1</option>
                        <option value="2" {{ $semestreSelectionne == '2' ? 'selected' : '' }}>Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('etudiant.bulletin.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 10px;">
                        <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($inscriptionActive)
        <!-- Informations étudiant et cursus -->
        <div class="row g-4 mb-4">
            <!-- Carte étudiant -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fw-bold text-white" 
                             style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 2.5rem;">
                            {{ strtoupper(substr($etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($etudiant->nom, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $etudiant->nom_complet }}</h4>
                        <p class="text-muted mb-3">{{ $etudiant->matricule }}</p>
                        
                        <div class="d-flex flex-column gap-2 text-start mt-4">
                            <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: #f8f9fa;">
                                <i class="bi bi-mortarboard text-primary"></i>
                                <span class="small">{{ $inscriptionActive->filiere->nom }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: #f8f9fa;">
                                <i class="bi bi-layers text-success"></i>
                                <span class="small">{{ $inscriptionActive->niveau->nom }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 p-2 rounded" style="background: #f8f9fa;">
                                <i class="bi bi-calendar-event text-info"></i>
                                <span class="small">{{ $anneeSelectionnee }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques globales -->
            <div class="col-lg-8">
                <div class="row g-3 h-100">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                         style="width: 56px; height: 56px; background: #fef3c7;">
                                        <i class="bi bi-star-fill fs-3" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="text-end">
                                        <h2 class="fw-bold mb-0" style="font-size: 3rem; color: {{ $stats['moyenne_generale'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                            {{ $stats['moyenne_generale'] }}
                                        </h2>
                                        <small class="text-muted">/20</small>
                                    </div>
                                </div>
                                <h6 class="fw-semibold mb-1">Moyenne Générale</h6>
                                <p class="text-muted small mb-0">
                                    @if($stats['moyenne_generale'] >= 16)
                                        Excellent - Très belle performance
                                    @elseif($stats['moyenne_generale'] >= 14)
                                        Très bien - Bon travail
                                    @elseif($stats['moyenne_generale'] >= 12)
                                        Bien - Continuez comme ça
                                    @elseif($stats['moyenne_generale'] >= 10)
                                        Passable - Année validée
                                    @else
                                        À améliorer - Redoublez d'efforts
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                         style="width: 56px; height: 56px; background: #dcfce7;">
                                        <i class="bi bi-trophy-fill fs-3" style="color: #16a34a;"></i>
                                    </div>
                                    <div class="text-end">
                                        <h2 class="fw-bold mb-0" style="font-size: 3rem; color: {{ $stats['taux_reussite'] >= 70 ? '#16a34a' : '#ef4444' }};">
                                            {{ $stats['taux_reussite'] }}%
                                        </h2>
                                    </div>
                                </div>
                                <h6 class="fw-semibold mb-1">Taux de Réussite</h6>
                                <p class="text-muted small mb-0">
                                    {{ $stats['matieres_reussies'] }} / {{ $stats['total_matieres'] }} matières validées
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                         style="width: 56px; height: 56px; background: #dbeafe;">
                                        <i class="bi bi-award-fill fs-3" style="color: #0284c7;"></i>
                                    </div>
                                    <div class="text-end">
                                        <h2 class="fw-bold mb-0" style="font-size: 3rem; color: #0284c7;">
                                            {{ $stats['credits_obtenus'] }}
                                        </h2>
                                        <small class="text-muted">/{{ $stats['credits_totaux'] }}</small>
                                    </div>
                                </div>
                                <h6 class="fw-semibold mb-1">Crédits ECTS</h6>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar" 
                                         style="width: {{ ($stats['credits_obtenus'] / $stats['credits_totaux']) * 100 }}%; background: #0284c7;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" 
                                         style="width: 56px; height: 56px; background: #f3e8ff;">
                                        <i class="bi bi-award fs-3" style="color: #9333ea;"></i>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge px-4 py-2" style="background: {{ $stats['moyenne_generale'] >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $stats['moyenne_generale'] >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 8px; font-size: 1rem;">
                                            {{ $stats['mention'] }}
                                        </span>
                                    </div>
                                </div>
                                <h6 class="fw-semibold mb-1">Mention</h6>
                                <p class="text-muted small mb-0">
                                    Résultat {{ $semestreSelectionne ? 'semestriel' : 'annuel' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des notes par matière -->
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            <i class="bi bi-journal-text text-primary me-2"></i>Relevé de Notes
                        </h5>
                        <p class="text-muted small mb-0">
                            Détail par matière - 
                            @if($semestreSelectionne)
                                Semestre {{ $semestreSelectionne }}
                            @else
                                Année complète
                            @endif
                            - {{ $anneeSelectionnee }}
                        </p>
                    </div>
                    <span class="badge bg-primary px-3 py-2">{{ $stats['total_matieres'] }} matière(s)</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($notesParMatiere->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none; width: 35%;">Matière</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Semestre</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Notes</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Coef.</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Moyenne</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Crédits</th>
                                    <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notesParMatiere as $item)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="px-4 py-4" style="border: none;">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; background: #dbeafe;">
                                                    <i class="bi bi-book" style="color: #0284c7;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item['matiere']->nom }}</div>
                                                    <small class="text-muted">{{ $item['matiere']->code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <span class="badge" style="background: #e0e7ff; color: #4f46e5; border-radius: 6px; padding: 6px 12px;">
                                                S{{ $item['matiere']->semestre }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#notes-{{ $item['matiere']->id }}" 
                                                    style="border-radius: 6px;">
                                                <i class="bi bi-eye me-1"></i>{{ $item['nb_notes'] }}
                                            </button>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <span class="fw-semibold">{{ $item['coefficient'] }}</span>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <div class="fw-bold fs-3" style="color: {{ $item['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                                {{ number_format($item['moyenne'], 2) }}
                                            </div>
                                            <small class="text-muted">/20</small>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <span class="badge px-3 py-2" style="background: {{ $item['moyenne'] >= 10 ? '#dcfce7' : '#f3f4f6' }}; color: {{ $item['moyenne'] >= 10 ? '#15803d' : '#6b7280' }}; border-radius: 8px;">
                                                {{ $item['moyenne'] >= 10 ? $item['credits'] : '0' }} ECTS
                                            </span>
                                        </td>
                                        <td class="py-4 text-center" style="border: none;">
                                            <span class="badge px-3 py-2" style="background: {{ $item['couleur'] == 'success' ? '#dcfce7' : '#fee2e2' }}; color: {{ $item['couleur'] == 'success' ? '#15803d' : '#dc2626' }}; border-radius: 8px;">
                                                <i class="bi bi-{{ $item['moyenne'] >= 10 ? 'check-circle' : 'x-circle' }} me-1"></i>
                                                {{ $item['statut'] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- Détail des notes (collapsible) -->
                                    <tr class="collapse" id="notes-{{ $item['matiere']->id }}">
                                        <td colspan="7" style="border: none; background: #f8f9fa; padding: 0;">
                                            <div class="p-4">
                                                <h6 class="fw-bold mb-3">Détail des notes :</h6>
                                                <div class="row g-3">
                                                    @foreach($item['notes'] as $note)
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: white; border: 1px solid #e5e7eb;">
                                                                <div>
                                                                    <div class="fw-semibold">{{ $note->type_evaluation }}</div>
                                                                    <small class="text-muted">{{ $note->date_saisie->format('d/m/Y') }}</small>
                                                                </div>
                                                                <div class="text-end">
                                                                    <div class="fw-bold fs-4" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                                                        {{ number_format($note->valeur, 2) }}
                                                                    </div>
                                                                    <small class="text-muted">/20</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #f8f9fa; border-top: 2px solid #e5e7eb;">
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-end fw-bold" style="border: none;">
                                        MOYENNE GÉNÉRALE :
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <div class="fw-bold" style="font-size: 2rem; color: {{ $stats['moyenne_generale'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                            {{ $stats['moyenne_generale'] }}
                                        </div>
                                        <small class="text-muted">/20</small>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <div class="fw-bold fs-5" style="color: #0284c7;">
                                            {{ $stats['credits_obtenus'] }}
                                        </div>
                                        <small class="text-muted">/{{ $stats['credits_totaux'] }}</small>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <span class="badge fs-6 px-4 py-2" style="background: {{ $stats['moyenne_generale'] >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $stats['moyenne_generale'] >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 10px;">
                                            {{ $stats['mention'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">Aucune note enregistrée pour cette période</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer du bulletin -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; background: linear-gradient(135deg, #f8f9fa 0%, #e5e7eb 100%);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle text-primary me-2"></i>Informations importantes
                        </h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>La validation d'une matière nécessite une moyenne ≥ 10/20</li>
                            <li>Les crédits ECTS sont acquis uniquement pour les matières validées</li>
                            <li>Le redoublement est requis si la moyenne générale < 10/20</li>
                        </ul>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted d-block mb-2">Bulletin édité le {{ now()->format('d/m/Y à H:i') }}</small>
                        <small class="text-muted d-block">Ce document est confidentiel et ne peut être reproduit sans autorisation</small>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Aucune inscription active trouvée pour l'année académique sélectionnée.
        </div>
    @endif
</div>

<style>
    @media print {
        .btn, .sidebar, .top-header, .card-header button { 
            display: none !important; 
        }
        .main-content { 
            margin-left: 0 !important; 
        }
        .card { 
            border: 1px solid #000 !important; 
            page-break-inside: avoid; 
        }
        .collapse {
            display: none !important;
        }
    }
</style>
@endsection