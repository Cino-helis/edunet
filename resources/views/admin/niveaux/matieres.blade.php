@extends('layouts.dashboard')

@section('title', 'Gérer les Matières du Niveau')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('admin.niveaux.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-1">Gérer les Matières</h2>
                <p class="text-muted mb-0">{{ $niveau->filiere->nom }} - {{ $niveau->nom }}</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('admin.niveaux.matieres.update', $niveau) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <p class="text-muted">
                            Sélectionnez les matières qui seront enseignées dans ce niveau.
                        </p>
                    </div>

                    <div class="row g-3">
                        @foreach($matieres as $matiere)
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="matieres[]" 
                                                   value="{{ $matiere->id }}"
                                                   id="matiere-{{ $matiere->id }}"
                                                   {{ $niveau->matieres->contains($matiere->id) ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="matiere-{{ $matiere->id }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="fw-semibold">{{ $matiere->nom }}</div>
                                                        <small class="text-muted">{{ $matiere->code }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <div>
                                                            <span class="badge bg-info">{{ $matiere->credits }} ECTS</span>
                                                        </div>
                                                        <small class="text-muted">Coef: {{ $matiere->coefficient }}</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer les matières
                        </button>
                        <a href="{{ route('admin.niveaux.index') }}" class="btn btn-outline-secondary px-4">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection