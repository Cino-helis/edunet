@extends('layouts.dashboard')

@section('title', 'Saisie Groupée de Notes')

@section('content')
<div>
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.notes.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-1">Saisie Groupée de Notes</h2>
            <p class="text-muted mb-0">Saisir les notes pour toute une classe en une seule fois</p>
        </div>
    </div>

    <div class="row">
        <!-- Formulaire de sélection -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>Paramètres
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="niveau_id" class="form-label fw-semibold">
                            Niveau <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="niveau_id" required>
                            <option value="">Sélectionner un niveau</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}">
                                    {{ $niveau->filiere->nom }} - {{ $niveau->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="matiere_id_groupee" class="form-label fw-semibold">
                            Matière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="matiere_id_groupee" required>
                            <option value="">Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">
                                    {{ $matiere->code }} - {{ $matiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="enseignant_id_groupee" class="form-label fw-semibold">
                            Enseignant <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="enseignant_id_groupee" required>
                            <option value="">Sélectionner un enseignant</option>
                            @foreach(\App\Models\Enseignant::orderBy('nom')->get() as $enseignant)
                                <option value="{{ $enseignant->id }}">
                                    {{ $enseignant->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type_evaluation_groupee" class="form-label fw-semibold">
                            Type d'évaluation <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="type_evaluation_groupee" required>
                            <option value="">Sélectionner</option>
                            <option value="CC">CC (Contrôle Continu)</option>
                            <option value="TP">TP (Travaux Pratiques)</option>
                            <option value="Examen">Examen</option>
                            <option value="Projet">Projet</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="annee_academique_groupee" class="form-label fw-semibold">
                            Année Académique <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="annee_academique_groupee" 
                               value="2024-2025" required>
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="chargerEtudiants()">
                        <i class="bi bi-search me-2"></i>Charger les étudiants
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="alert alert-info">
                <h6 class="alert-heading fw-bold">
                    <i class="bi bi-lightbulb me-2"></i>Astuce
                </h6>
                <small>
                    Vous pouvez utiliser la touche <kbd>Tab</kbd> pour naviguer rapidement 
                    entre les champs de notes.
                </small>
            </div>
        </div>

        <!-- Liste des étudiants -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Liste des Étudiants
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="etudiants-container">
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p>Sélectionnez un niveau pour afficher les étudiants</p>
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
    const niveauId = document.getElementById('niveau_id').value;
    const matiereId = document.getElementById('matiere_id_groupee').value;
    const enseignantId = document.getElementById('enseignant_id_groupee').value;
    const typeEvaluation = document.getElementById('type_evaluation_groupee').value;
    const anneeAcademique = document.getElementById('annee_academique_groupee').value;

    if (!niveauId) {
        alert('Veuillez sélectionner un niveau');
        return;
    }

    if (!matiereId || !enseignantId || !typeEvaluation) {
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }

    // Appel AJAX pour récupérer les étudiants
    fetch(`{{ route('admin.api.etudiants-by-niveau') }}?niveau_id=${niveauId}`)
        .then(response => response.json())
        .then(data => {
            etudiants = data;
            afficherFormulaire(data, matiereId, enseignantId, typeEvaluation, anneeAcademique);
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des étudiants');
        });
}

function afficherFormulaire(etudiants, matiereId, enseignantId, typeEvaluation, anneeAcademique) {
    if (etudiants.length === 0) {
        document.getElementById('etudiants-container').innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <p>Aucun étudiant trouvé dans ce niveau</p>
            </div>
        `;
        return;
    }

    let html = `
        <form action="{{ route('admin.notes.store-groupee') }}" method="POST">
            @csrf
            <input type="hidden" name="matiere_id" value="${matiereId}">
            <input type="hidden" name="enseignant_id" value="${enseignantId}">
            <input type="hidden" name="type_evaluation" value="${typeEvaluation}">
            <input type="hidden" name="annee_academique" value="${anneeAcademique}">
            
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">N°</th>
                            <th class="py-3">Matricule</th>
                            <th class="py-3">Nom Complet</th>
                            <th class="py-3 text-center" width="150">Note /20</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    etudiants.forEach((etudiant, index) => {
        html += `
            <tr>
                <td class="px-4 py-3">${index + 1}</td>
                <td class="py-3">
                    <span class="badge bg-primary">${etudiant.matricule}</span>
                </td>
                <td class="py-3 fw-semibold">${etudiant.prenom} ${etudiant.nom}</td>
                <td class="py-3">
                    <input type="hidden" name="notes[${index}][etudiant_id]" value="${etudiant.id}">
                    <input type="number" 
                           name="notes[${index}][valeur]" 
                           class="form-control text-center" 
                           min="0" 
                           max="20" 
                           step="0.01" 
                           placeholder="0.00"
                           required>
                </td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${etudiants.length}</strong> étudiant(s) à noter
                    </div>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-all me-2"></i>Enregistrer toutes les notes
                    </button>
                </div>
            </div>
        </form>
    `;

    document.getElementById('etudiants-container').innerHTML = html;
}
</script>
@endpush
@endsection