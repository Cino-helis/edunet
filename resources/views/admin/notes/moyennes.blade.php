@extends('layouts.dashboard')

@section('title', 'Bulletin de Notes')

@section('content')
<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.etudiants.show', $etudiant) }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Bulletin de Notes</h2>
                <p class="text-muted mb-0">{{ $etudiant->nom_complet }} - {{ $etudiant->matricule }}</p>
            </div>
        </div>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimer
        </button>
    </div>

    <!-- Carte d'identité de l'étudiant -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <strong>Nom complet :</strong><br>
                    {{ $etudiant->nom_complet }}
                </div>
                <div class="col-md-3">
                    <strong>Matricule :</strong><br>
                    {{ $etudiant->matricule }}
                </div>
                <div class="col-md-3">
                    <strong>Date de naissance :</strong><br>
                    {{ $etudiant->date_naissance->format('d/m/Y') }}
                </div>
                <div class="col-md-3">
                    <strong>Âge :</strong><br>
                    {{ $etudiant->age }} ans
                </div>
            </div>
        </div>
    </div>

    <!-- Moyenne Générale -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="bi bi-star-fill fs-1 text-warning mb-3 d-block"></i>
                    <h3 class="display-4 fw-bold text-{{ $moyenneGenerale >= 10 ? 'success' : 'danger' }}">
                        {{ number_format($moyenneGenerale, 2) }}
                    </h3>
                    <p class="text-muted mb-0">Moyenne Générale / 20</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="bi bi-book-fill fs-1 text-primary mb-3 d-block"></i>
                    <h3 class="display-4 fw-bold text-primary">
                        {{ $notesParMatiere->count() }}
                    </h3>
                    <p class="text-muted mb-0">Matières Évaluées</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <i class="bi bi-trophy-fill fs-1 text-success mb-3 d-block"></i>
                    <h3 class="display-4 fw-bold text-success">
                        {{ $notesParMatiere->filter(function($item) { return $item['moyenne'] >= 10; })->count() }}/{{ $notesParMatiere->count() }}
                    </h3>
                    <p class="text-muted mb-0">Matières Réussies</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail par matière -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>Détail des Notes par Matière
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Matière</th>
                            <th class="py-3 text-center">Crédits</th>
                            <th class="py-3 text-center">Coefficient</th>
                            <th class="py-3 text-center">Notes</th>
                            <th class="py-3 text-center">Moyenne</th>
                            <th class="py-3 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notesParMatiere as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold">{{ $item['matiere']->nom }}</div>
                                    <small class="text-muted">{{ $item['matiere']->code }}</small>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-info">{{ $item['credits'] }} ECTS</span>
                                </td>
                                <td class="py-3 text-center">
                                    <strong>{{ $item['coefficient'] }}</strong>
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-{{ $item['matiere']->id }}">
                                        {{ $item['notes']->count() }} note(s)
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="fs-5 fw-bold text-{{ $item['moyenne'] >= 10 ? 'success' : 'danger' }}">
                                        {{ number_format($item['moyenne'], 2) }}/20
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    @if($item['moyenne'] >= 10)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Validé
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Non Validé
                                        </span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal détail des notes -->
                            <div class="modal fade" id="modal-{{ $item['matiere']->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $item['matiere']->nom }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Type</th>
                                                        <th>Note</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item['notes'] as $note)
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-info">{{ $note->type_evaluation }}</span>
                                                            </td>
                                                            <td>
                                                                <strong class="text-{{ $note->valeur >= 10 ? 'success' : 'danger' }}">
                                                                    {{ number_format($note->valeur, 2) }}/20
                                                                </strong>
                                                            </td>
                                                            <td>
                                                                <small>{{ $note->date_saisie->format('d/m/Y') }}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-end fw-bold">MOYENNE GÉNÉRALE :</td>
                            <td class="py-3 text-center">
                                <span class="fs-4 fw-bold text-{{ $moyenneGenerale >= 10 ? 'success' : 'danger' }}">
                                    {{ number_format($moyenneGenerale, 2) }}/20
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                @if($moyenneGenerale >= 10)
                                    <span class="badge bg-success fs-6">ADMIS</span>
                                @else
                                    <span class="badge bg-danger fs-6">AJOURNÉ</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .sidebar, .top-header { display: none !important; }
    .main-content { margin-left: 0 !important; }
}
</style>
@endsection