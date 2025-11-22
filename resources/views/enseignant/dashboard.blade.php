@extends('layouts.dashboard')

@section('title', 'Tableau de bord enseignant')

@section('content')
<div>
    <!-- En-tête -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Tableau de bord enseignant</h2>
        <p class="text-muted mb-0">Gestion de vos classes et saisie des notes</p>
    </div>

    <!-- Statistiques Cards -->
    <div class="row g-3 mb-4">
        <!-- Classes assignées -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #e0f2fe;">
                            <i class="bi bi-people-fill fs-4" style="color: #0284c7;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Classes assignées
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">{{ $stats['nb_matieres'] }}</h2>
                </div>
            </div>
        </div>

        <!-- Total étudiants -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #dcfce7;">
                            <i class="bi bi-person-check-fill fs-4" style="color: #16a34a;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Total étudiants
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">{{ $stats['nb_etudiants'] }}</h2>
                </div>
            </div>
        </div>

        <!-- Moyenne générale -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #fef3c7;">
                            <i class="bi bi-star-fill fs-4" style="color: #f59e0b;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Moyenne générale
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">{{ number_format($stats['moyenne_notes'], 1) }}<span class="fs-5 text-muted">/20</span></h2>
                </div>
            </div>
        </div>

        <!-- Classes en cours -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: #f3e8ff;">
                            <i class="bi bi-clock-fill fs-4" style="color: #9333ea;"></i>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-medium" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            Classes en cours
                        </small>
                    </div>
                    <h2 class="fw-bold mb-0" style="font-size: 2.5rem;">{{ $affectations->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes classes -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold mb-0">Mes classes</h5>
                <a href="{{ route('enseignant.notes.saisie-groupee') }}" class="btn btn-primary" style="border-radius: 10px;">
                    <i class="bi bi-pencil-square me-2"></i>Saisir des notes
                </a>
            </div>

            @if($statsParMatiere->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Aucune classe assignée</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle" style="border: none;">
                        <thead style="background: #f8f9fa; border: none;">
                            <tr>
                                <th class="fw-semibold text-muted text-uppercase py-3" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Matière</th>
                                <th class="fw-semibold text-muted text-uppercase py-3 text-center" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Niveau</th>
                                <th class="fw-semibold text-muted text-uppercase py-3 text-center" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Étudiants</th>
                                <th class="fw-semibold text-muted text-uppercase py-3 text-center" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Moyenne</th>
                                <th class="fw-semibold text-muted text-uppercase py-3 text-center" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Statut</th>
                                <th class="fw-semibold text-muted text-uppercase py-3 text-end" style="font-size: 0.75rem; border: none; letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsParMatiere as $stat)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="py-4" style="border: none;">
                                        <div>
                                            <div class="fw-semibold">{{ $stat['matiere']->nom }}</div>
                                            <small class="text-muted">{{ $stat['matiere']->code }}</small>
                                        </div>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <span class="text-muted">{{ $stat['niveau']->nom }}</span>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <span class="fw-semibold">{{ $stat['nb_notes'] > 0 ? $stat['nb_notes'] : '-' }}</span>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        <span class="fw-bold" style="color: {{ $stat['moyenne'] >= 10 ? '#16a34a' : '#ef4444' }};">
                                            {{ number_format($stat['moyenne'], 1) }}/20
                                        </span>
                                    </td>
                                    <td class="py-4 text-center" style="border: none;">
                                        @if($stat['taux_reussite'] >= 70)
                                            <span class="badge px-3 py-2" style="background: #dbeafe; color: #1e40af; border-radius: 8px; font-weight: 500;">
                                                En cours
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="background: #dcfce7; color: #15803d; border-radius: 8px; font-weight: 500;">
                                                Terminé
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-end" style="border: none;">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" style="border-radius: 8px 0 0 8px;" title="Saisir">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" style="border-radius: 0;" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" style="border-radius: 0 8px 8px 0;" title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Dernières notes saisies -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Dernières notes saisies</h5>

            @if($notesRecentes->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Aucune note saisie récemment</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($notesRecentes->take(6) as $note)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background: #f8f9fa;">
                                <!-- Avatar -->
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.1rem;">
                                    {{ strtoupper(substr($note->etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($note->etudiant->nom, 0, 1)) }}
                                </div>
                                
                                <!-- Infos -->
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $note->etudiant->nom_complet }}</div>
                                    <small class="text-muted">{{ $note->matiere->code }}</small>
                                </div>
                                
                                <!-- Note -->
                                <div class="text-end">
                                    <div class="fw-bold fs-5" style="color: {{ $note->valeur >= 10 ? '#16a34a' : '#ef4444' }};">
                                        {{ number_format($note->valeur, 0) }}/20
                                    </div>
                                    <span class="badge px-2 py-1" style="background: {{ $note->valeur >= 10 ? '#dcfce7' : '#fee2e2' }}; color: {{ $note->valeur >= 10 ? '#15803d' : '#dc2626' }}; border-radius: 6px; font-size: 0.7rem;">
                                        {{ $note->valeur >= 10 ? 'Validé' : 'Échoué' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Styles supplémentaires pour le design moderne */
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
    
    .table tbody tr {
        transition: background-color 0.2s;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .btn {
        transition: all 0.2s;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endsection