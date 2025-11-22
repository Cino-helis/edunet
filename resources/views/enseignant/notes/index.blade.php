@extends('layouts.dashboard')

@section('title', 'Mes Notes Saisies')

@section('content')
<div>
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Notes Saisies</h2>
            <p class="text-muted mb-0">Historique de toutes vos saisies de notes</p>
        </div>
        <a href="{{ route('enseignant.notes.saisie-groupee') }}" class="btn btn-primary" style="border-radius: 10px;">
            <i class="bi bi-plus-circle me-2"></i>Saisir des notes
        </a>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('enseignant.notes.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small text-muted">Matière</label>
                    <select name="matiere_id" class="form-select" style="border-radius: 10px;">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">Type</label>
                    <select name="type_evaluation" class="form-select" style="border-radius: 10px;">
                        <option value="">Tous les types</option>
                        <option value="CC" {{ request('type_evaluation') == 'CC' ? 'selected' : '' }}>CC</option>
                        <option value="TP" {{ request('type_evaluation') == 'TP' ? 'selected' : '' }}>TP</option>
                        <option value="Examen" {{ request('type_evaluation') == 'Examen' ? 'selected' : '' }}>Examen</option>
                        <option value="Projet" {{ request('type_evaluation') == 'Projet' ? 'selected' : '' }}>Projet</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small text-muted">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px;">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small text-muted">&nbsp;</label>
                    <a href="{{ route('enseignant.notes.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 10px;">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #dbeafe;">
                            <i class="bi bi-clipboard-check fs-4" style="color: #0284c7;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Total Notes</small>
                            <h4 class="mb-0 fw-bold">{{ $notes->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #fef3c7;">
                            <i class="bi bi-star fs-4" style="color: #f59e0b;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Moyenne</small>
                            <h4 class="mb-0 fw-bold">{{ number_format($notes->avg('valeur') ?? 0, 1) }}/20</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3" 
                             style="width: 48px; height: 48px; background: #dcfce7;">
                            <i class="bi bi-trophy fs-4" style="color: #16a34a;"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Taux Réussite</small>
                            <h4 class="mb-0 fw-bold">
                                {{ $notes->count() > 0 ? number_format(($notes->where('valeur', '>=', 10)->count() / $notes->count()) * 100, 1) : 0 }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des notes -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-0">
            @if($notes->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Aucune note trouvée</p>
                    <a href="{{ route('enseignant.notes.saisie-groupee') }}" class="btn btn-primary" style="border-radius: 10px;">
                        <i class="bi bi-plus-circle me-2"></i>Saisir des notes
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="px-4 py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Étudiant</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Matière</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Type</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Note</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Date</th>
                                <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none;">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes as $note)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="px-4 py-3" style="border: none;">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.9rem;">
                                                {{ strtoupper(substr($note->etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($note->etudiant->nom, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $note->etudiant->nom_complet }}</div>
                                                <small class="text-muted">{{ $note->etudiant->matricule }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3" style="border: none;">
                                        <div class="fw-semibold">{{ $note->matiere->nom }}</div>
                                        <small class="text-muted">{{ $note->matiere->code }}</small>
                                    </td>
                                    <td class="py-3 text-center" style="border: none;">
                                        <span class="badge px-2 py-1" style="background: #dbeafe; color: #1e40af; border-radius: 6px;">
                                            {{ $note->type_evaluation }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center" style="border: none;">
                                        <span class="fs-5 fw-bold" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                            {{ number_format($note->valeur, 2) }}/20
                                        </span>
                                    </td>
                                    <td class="py-3" style="border: none;">
                                        <small class="text-muted">{{ $note->date_saisie->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td class="py-3 text-center" style="border: none;">
                                        @if($note->valeur >= 10)
                                            <span class="badge px-3 py-2" style="background: #dcfce7; color: #15803d; border-radius: 8px;">
                                                Validé
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="background: #fee2e2; color: #dc2626; border-radius: 8px;">
                                                Échoué
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $notes->withQueryString()->links() }}
    </div>
</div>
@endsection