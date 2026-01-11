@extends('layouts.dashboard')

@section('title', 'Modifier une Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Modifier l'Inscription</h2>
                <p class="text-muted mb-0">{{ $inscription->etudiant->nom_complet }}</p>
            </div>
        </div>

        <!-- Informations de l'étudiant -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <span class="fw-bold" style="color: #0284c7; font-size: 1.5rem;">
                            {{ strtoupper(substr($inscription->etudiant->prenom, 0, 1)) }}{{ strtoupper(substr($inscription->etudiant->nom, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-1" style="color: #0369a1;">
                            {{ $inscription->etudiant->nom_complet }}
                        </h5>
                        <p class="mb-0" style="color: #075985;">
                            <i class="bi bi-person-badge me-1"></i>
                            {{ $inscription->etudiant->matricule }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge px-3 py-2" style="background: white; color: #0369a1; border-radius: 10px;">
                            <i class="bi bi-calendar me-1"></i>
                            Inscrit le {{ $inscription->date_inscription->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.inscriptions.update', $inscription) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Filière -->
                    <div class="mb-4">
                        <label for="filiere_id" class="form-label fw-semibold">
                            Filière <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('filiere_id') is-invalid @enderror" 
                                id="filiere_id" 
                                name="filiere_id" 
                                required>
                            <option value="">Sélectionner une filière</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" 
                                        {{ old('filiere_id', $inscription->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Niveau -->
                    <div class="mb-4">
                        <label for="niveau_id" class="form-label fw-semibold">
                            Niveau <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('niveau_id') is-invalid @enderror" 
                                id="niveau_id" 
                                name="niveau_id" 
                                required>
                            <option value="">Sélectionner un niveau</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}" 
                                        {{ old('niveau_id', $inscription->niveau_id) == $niveau->id ? 'selected' : '' }}>
                                    {{ $niveau->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('niveau_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Année académique et Statut -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="annee_academique" class="form-label fw-semibold">
                                Année Académique <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('annee_academique') is-invalid @enderror" 
                                   id="annee_academique" 
                                   name="annee_academique" 
                                   value="{{ old('annee_academique', $inscription->annee_academique) }}"
                                   placeholder="2025-2026"
                                   required>
                            @error('annee_academique')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="statut" class="form-label fw-semibold">
                                Statut <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" 
                                    name="statut" 
                                    required>
                                <option value="en_cours" {{ old('statut', $inscription->statut) == 'en_cours' ? 'selected' : '' }}>
                                    En cours
                                </option>
                                <option value="validee" {{ old('statut', $inscription->statut) == 'validee' ? 'selected' : '' }}>
                                    Validée
                                </option>
                                <option value="suspendue" {{ old('statut', $inscription->statut) == 'suspendue' ? 'selected' : '' }}>
                                    Suspendue
                                </option>
                                <option value="abandonnee" {{ old('statut', $inscription->statut) == 'abandonnee' ? 'selected' : '' }}>
                                    Abandonnée
                                </option>
                            </select>
                            @error('statut')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations sur le statut -->
                    <div class="alert alert-info border-0 mb-4" style="border-radius: 12px;">
                        <h6 class="fw-bold mb-2">
                            <i class="bi bi-info-circle me-2"></i>À propos des statuts
                        </h6>
                        <ul class="small mb-0 ps-3">
                            <li><strong>En cours :</strong> L'étudiant suit actuellement les cours</li>
                            <li><strong>Validée :</strong> L'année a été validée avec succès</li>
                            <li><strong>Suspendue :</strong> Inscription temporairement interrompue</li>
                            <li><strong>Abandonnée :</strong> L'étudiant a abandonné définitivement</li>
                        </ul>
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Historique de l'inscription -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history text-primary me-2"></i>Historique
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Date d'inscription</small>
                        <div class="fw-semibold">
                            <i class="bi bi-calendar-plus text-primary me-1"></i>
                            {{ $inscription->date_inscription->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Dernière modification</small>
                        <div class="fw-semibold">
                            <i class="bi bi-clock text-warning me-1"></i>
                            {{ $inscription->updated_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Charger les niveaux selon la filière sélectionnée
    document.getElementById('filiere_id').addEventListener('change', function() {
        const filiereId = this.value;
        const niveauSelect = document.getElementById('niveau_id');
        
        if (!filiereId) {
            niveauSelect.innerHTML = '<option value="">Sélectionner d\'abord une filière</option>';
            niveauSelect.disabled = true;
            return;
        }
        
        // Désactiver temporairement
        niveauSelect.disabled = true;
        niveauSelect.innerHTML = '<option value="">Chargement...</option>';
        
        // Charger les niveaux via AJAX
        fetch(`{{ route('admin.api.niveaux-by-filiere') }}?filiere_id=${filiereId}`)
            .then(response => response.json())
            .then(niveaux => {
                let options = '<option value="">Sélectionner un niveau</option>';
                niveaux.forEach(niveau => {
                    const selected = niveau.id == {{ old('niveau_id', $inscription->niveau_id) }} ? 'selected' : '';
                    options += `<option value="${niveau.id}" ${selected}>${niveau.nom}</option>`;
                });
                niveauSelect.innerHTML = options;
                niveauSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erreur:', error);
                niveauSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                alert('Erreur lors du chargement des niveaux');
            });
    });

    // Trigger au chargement de la page si une filière est déjà sélectionnée
    window.addEventListener('load', function() {
        const filiereSelect = document.getElementById('filiere_id');
        if (filiereSelect.value) {
            // Ne rien faire, les niveaux sont déjà chargés par le contrôleur
        }
    });
</script>
@endpush
@endsection