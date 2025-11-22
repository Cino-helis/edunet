@extends('layouts.dashboard')

@section('title', 'Mes Classes')

@section('content')
<div>
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Mes Classes</h2>
        <p class="text-muted mb-0">Liste de toutes vos classes assignées</p>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            @if($affectations->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted">Aucune classe assignée</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Matière</th>
                                <th>Niveau</th>
                                <th>Filière</th>
                                <th class="text-center">Étudiants</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affectations as $affectation)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $affectation->matiere->nom }}</div>
                                        <small class="text-muted">{{ $affectation->matiere->code }}</small>
                                    </td>
                                    <td>{{ $affectation->niveau->nom }}</td>
                                    <td>{{ $affectation->niveau->filiere->nom }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ $affectation->niveau->inscriptions->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('enseignant.notes.saisie-groupee') }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Saisir notes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection