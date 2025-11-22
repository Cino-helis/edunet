@extends('layouts.dashboard')

@section('title', 'Saisie Groupée de Notes')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Saisie Groupée de Notes</h2>
        <p class="text-muted mb-0">Saisir les notes pour toute une classe en une seule fois</p>
    </div>

    <div class="row g-4">
        <!-- Formulaire de sélection -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 16px; top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-funnel text-primary me-2"></i>Paramètres de saisie
                    </h5>

                    <form id="filterForm">
                        <!-- Matière et Niveau -->
                        <div class="mb-3">
                            <label for="affectation_id" class="form-label fw-semibold">
                                Classe <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="affectation_id" required style="border-radius: 10px;">
                                <option value="">Sélectionner une classe</option>
                                @foreach($affectations as $affectation)
                                    <option value="{{ $affectation->id }}" 
                                            data-matiere="{{ $affectation->matiere_id }}"
                                            data-niveau="{{ $affectation->niveau_id }}">
                                        {{ $affectation->matiere->nom }} - {{ $affectation->niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type d'évaluation -->
                        <div class="mb-3">
                            <label for="type_evaluation" class="form-label fw-semibold">
                                Type d'évaluation <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="type_evaluation" required style="border-radius: 10px;">
                                <option value="">Sélectionner</option>
                                <option value="CC">CC (Contrôle Continu)</option>
                                <option value="TP">TP (Travaux Pratiques)</option>
                                <option value="Examen">Examen</option>
                                <option value="Projet">Projet</option>
                            </select>
                        </div>

                        <!-- Année académique -->
                        <div class="mb-4">
                            <label for="annee_academique" class="form-label fw-semibold">
                                Année Académique <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="annee_academique" 
                                   value="2024-2025" required style="border-radius: 10px;">
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="chargerEtudiants()" 
                                style="border-radius: 10px; padding: 12px;">
                            <i class="bi bi-search me-2"></i>Charger les étudiants
                        </button>
                    </form>

                    <!-- Info box -->
                    <div class="alert alert-info mt-4" style="border-radius: 10px; border: none; background: #e0f2fe; color: #0369a1;">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle fs-5 me-2"></i>
                            <div>
                                <h6 class="fw-bold mb-2">💡 Astuce</h6>
                                <small>Utilisez la touche <kbd>Tab</kbd> pour naviguer rapidement entre les champs de notes.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des étudiants -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-0">
                    <div id="etudiants-container">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
                                <h5 class="fw-semibold text-muted">Sélectionnez une classe</h5>
                                <p class="text-muted small mb-0">Choisissez une classe dans le panneau de gauche pour afficher les étudiants</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let etudiants = [];

function chargerEtudiants() {
    const affectationSelect = document.getElementById('affectation_id');
    const typeEvaluation = document.getElementById('type_evaluation').value;
    const anneeAcademique = document.getElementById('annee_academique').value;

    if (!affectationSelect.value) {
        alert('Veuillez sélectionner une classe');
        return;
    }

    if (!typeEvaluation) {
        alert('Veuillez sélectionner un type d\'évaluation');
        return;
    }

    const selectedOption = affectationSelect.options[affectationSelect.selectedIndex];
    const niveauId = selectedOption.getAttribute('data-niveau');
    const matiereId = selectedOption.getAttribute('data-matiere');

    // Afficher un loader
    document.getElementById('etudiants-container').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="text-muted">Chargement des étudiants...</p>
        </div>
    `;

    // Appel AJAX
    fetch(`{{ route('enseignant.api.etudiants-by-niveau') }}?niveau_id=${niveauId}`)
        .then(response => response.json())
        .then(data => {
            etudiants = data;
            afficherFormulaire(data, matiereId, typeEvaluation, anneeAcademique);
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('etudiants-container').innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger d-block mb-3"></i>
                    <p class="text-danger">Erreur lors du chargement des étudiants</p>
                </div>
            `;
        });
}

function afficherFormulaire(etudiants, matiereId, typeEvaluation, anneeAcademique) {
    if (etudiants.length === 0) {
        document.getElementById('etudiants-container').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted">Aucun étudiant trouvé dans cette classe</p>
            </div>
        `;
        return;
    }

    let html = `
        <form action="{{ route('enseignant.notes.store-groupee') }}" method="POST">
            @csrf
            <input type="hidden" name="matiere_id" value="${matiereId}">
            <input type="hidden" name="type_evaluation" value="${typeEvaluation}">
            <input type="hidden" name="annee_academique" value="${anneeAcademique}">
            
            <!-- Header -->
            <div class="p-4 border-bottom" style="background: #f8f9fa;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-1">Liste des étudiants</h5>
                        <small class="text-muted">${etudiants.length} étudiant(s) inscrit(s)</small>
                    </div>
                    <span class="badge bg-primary px-3 py-2" style="border-radius: 8px;">
                        ${typeEvaluation}
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead style="background: #f8f9fa; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th class="px-4 py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">N°</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Matricule</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem; border: none;">Nom Complet</th>
                            <th class="py-3 fw-semibold text-muted text-uppercase text-center" style="font-size: 0.75rem; border: none; width: 200px;">Note /20</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    etudiants.forEach((etudiant, index) => {
        html += `
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td class="px-4 py-3" style="border: none;">
                    <span class="text-muted fw-medium">${index + 1}</span>
                </td>
                <td class="py-3" style="border: none;">
                    <span class="badge" style="background: #dbeafe; color: #1e40af; border-radius: 6px; padding: 6px 12px;">
                        ${etudiant.matricule}
                    </span>
                </td>
                <td class="py-3" style="border: none;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-white" 
                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.9rem;">
                            ${etudiant.prenom.charAt(0).toUpperCase()}${etudiant.nom.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="fw-semibold">${etudiant.prenom} ${etudiant.nom}</div>
                            <small class="text-muted">${etudiant.user.email}</small>
                        </div>
                    </div>
                </td>
                <td class="py-3 text-center" style="border: none;">
                    <input type="hidden" name="notes[${index}][etudiant_id]" value="${etudiant.id}">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <input type="number" 
                               name="notes[${index}][valeur]" 
                               class="form-control text-center fw-bold fs-5" 
                               min="0" 
                               max="20" 
                               step="0.25" 
                               placeholder="0.00"
                               style="max-width: 120px; border-radius: 10px; border: 2px solid #e5e7eb;"
                               required
                               onchange="updateNoteColor(this)">
                        <span class="text-muted">/20</span>
                    </div>
                </td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>
            </div>
            
            <!-- Footer avec bouton de soumission -->
            <div class="p-4 border-top" style="background: #f8f9fa;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">${etudiants.length} étudiant(s) à noter</div>
                        <small class="text-muted">Toutes les notes seront enregistrées simultanément</small>
                    </div>
                    <button type="submit" class="btn btn-success px-4 py-3" style="border-radius: 10px;">
                        <i class="bi bi-check-all me-2"></i>Enregistrer toutes les notes
                    </button>
                </div>
            </div>
        </form>
    `;

    document.getElementById('etudiants-container').innerHTML = html;

    // Focus sur le premier champ de note
    setTimeout(() => {
        document.querySelector('input[name="notes[0][valeur]"]')?.focus();
    }, 100);
}

// Changer la couleur de la bordure selon la note
function updateNoteColor(input) {
    const value = parseFloat(input.value);
    if (isNaN(value)) return;
    
    if (value >= 16) {
        input.style.borderColor = '#10b981';
        input.style.color = '#10b981';
    } else if (value >= 14) {
        input.style.borderColor = '#3b82f6';
        input.style.color = '#3b82f6';
    } else if (value >= 10) {
        input.style.borderColor = '#f59e0b';
        input.style.color = '#f59e0b';
    } else {
        input.style.borderColor = '#ef4444';
        input.style.color = '#ef4444';
    }
}
</script>
@endpush

<style>
    /* Sticky sidebar */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            top: 100px;
        }
    }
    
    /* Amélioration visuelle des inputs */
    input[type="number"]:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        border-color: #3b82f6 !important;
    }
    
    /* Animation de chargement */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .spinner-border {
        animation: spin 1s linear infinite;
    }
</style>
@endsection