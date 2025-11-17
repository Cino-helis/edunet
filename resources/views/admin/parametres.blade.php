@extends('layouts.dashboard')

@section('title', 'Paramètres Système')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Paramètres Système</h2>
        <p class="text-muted mb-0">Configuration et maintenance de l'application</p>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Informations Système -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations Système
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td class="fw-semibold">Nom de l'application</td>
                            <td>{{ $parametres['app_name'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Environnement</td>
                            <td>
                                <span class="badge bg-{{ $parametres['app_env'] == 'production' ? 'success' : 'warning' }}">
                                    {{ strtoupper($parametres['app_env']) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Mode Debug</td>
                            <td>
                                <span class="badge bg-{{ $parametres['app_debug'] ? 'danger' : 'success' }}">
                                    {{ $parametres['app_debug'] ? 'Activé' : 'Désactivé' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Cache Driver</td>
                            <td>{{ $parametres['cache_driver'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Version Laravel</td>
                            <td>{{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Version PHP</td>
                            <td>{{ PHP_VERSION }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Espace Disque -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-hdd me-2"></i>Espace Disque
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Espace utilisé</span>
                            <strong>{{ $diskSpace['used'] }}</strong>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success"
                            style="width: {{ $diskSpace['used_raw'] / $diskSpace['total_raw'] * 100 }}%">
                            {{ number_format($diskSpace['used_raw'] / $diskSpace['total_raw'] * 100, 1) }}%
                        </div>
                    </div>
                    <span>{{ $diskSpace['used'] }} utilisés sur {{ $diskSpace['total'] }}.</span>
                        

                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="fw-semibold">Total</td>
                            <td class="text-end">{{ $diskSpace['total'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Utilisé</td>
                            <td class="text-end">{{$diskSpace['used'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Disponible</td>
                            <td class="text-end">{{$diskSpace['free'] }}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Cache</td>
                            <td class="text-end">{{ $cacheSize }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions de Maintenance -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-tools me-2"></i>Actions de Maintenance
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Vider le cache -->
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-trash fs-1 text-danger mb-3 d-block"></i>
                            <h6 class="fw-bold">Vider le Cache</h6>
                            <p class="small text-muted">Supprimer tous les fichiers de cache</p>
                            <form action="{{ route('admin.parametres.clear-cache') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir vider le cache ?')">
                                    <i class="bi bi-trash me-1"></i>Vider
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Optimiser -->
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-lightning-charge fs-1 text-success mb-3 d-block"></i>
                            <h6 class="fw-bold">Optimiser l'Application</h6>
                            <p class="small text-muted">Mettre en cache les configurations</p>
                            <form action="{{ route('admin.parametres.optimize') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-lightning-charge me-1"></i>Optimiser
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sauvegarde -->
                <div class="col-md-4">
                    <div class="card bg-light h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-cloud-download fs-1 text-primary mb-3 d-block"></i>
                            <h6 class="fw-bold">Sauvegarder la Base</h6>
                            <p class="small text-muted">Créer une copie de la base de données</p>
                            <form action="{{ route('admin.parametres.backup') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-cloud-download me-1"></i>Sauvegarder
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes de Sécurité -->
    @if($parametres['app_debug'])
        <div class="alert alert-danger mt-4">
            <h6 class="alert-heading fw-bold">
                <i class="bi bi-exclamation-triangle me-2"></i>Alerte de Sécurité !
            </h6>
            <p class="mb-0">
                Le mode debug est activé. Désactivez-le en production pour des raisons de sécurité.
                <br>Modifiez <code>APP_DEBUG=false</code> dans votre fichier <code>.env</code>
            </p>
        </div>
    @endif
</div>

@endsection