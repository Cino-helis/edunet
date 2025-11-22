<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduNet - Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background-color: #f3f4f6;
            color: #2563eb;
        }
        
        .menu-item.active {
            background-color: #eff6ff;
            color: #2563eb;
            border-left-color: #2563eb;
            font-weight: 500;
        }
        
        .menu-item i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .search-box {
            position: relative;
            width: 400px;
        }
        
        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
        }
        
        .search-box i {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin: 0.5rem 0;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .stat-change {
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .stat-change.positive {
            color: #10b981;
        }
        
        /* Performance Section */
        .performance-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        
        .performance-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .performance-item:last-child {
            border-bottom: none;
        }
        
        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background-color: #f3f4f6;
        }
        
        .progress-bar-custom {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s;
        }
        
        /* Activity Section */
        .activity-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        
        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 0.875rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 0.5rem;
            flex-shrink: 0;
        }
        
        .activity-dot.green {
            background-color: #10b981;
        }
        
        .activity-dot.blue {
            background-color: #3b82f6;
        }
        
        /* Action Buttons */
        .action-btn {
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
        }
        
        .action-btn.primary {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        
        .action-btn.primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .action-btn.secondary {
            background: white;
            color: #374151;
        }
        
        .action-btn.secondary:hover {
            background: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .user-profile:hover {
            background: #f3f4f6;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .notification-badge {
            position: relative;
        }
        
        .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-box {
                width: 100%;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="sidebar-logo">
                    <i class="bi bi-mortarboard-fill text-white fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">EduNet</h5>
                    <small class="text-muted">Gestion des notes</small>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            @if(auth()->user()->type_utilisateur === 'administrateur')
            <!-- Menu Admin -->
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('admin.niveaux.index') }}" class="menu-item {{ request()->routeIs('admin.niveaux.*') ? 'active' : '' }}">
                <i class="bi bi-layer-forward"></i>
                <span>Niveaux</span>
            </a>

            <a href="{{ route('admin.affectations.index') }}" class="menu-item {{ request()->routeIs('admin.affectations.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3-fill"></i>
                <span>Affectations</span>
            /a>
        
            <a href="{{ route('admin.filieres.index') }}" class="menu-item {{ request()->routeIs('admin.filieres.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard"></i>
                <span>Filières</span>
            </a>
        
            <a href="{{ route('admin.matieres.index') }}" class="menu-item {{ request()->routeIs('admin.matieres.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>Matières</span>
            </a>
        
            <a href="{{ route('admin.etudiants.index') }}" class="menu-item {{ request()->routeIs('admin.etudiants.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Étudiants</span>
            </a>
        
            <a href="{{ route('admin.enseignants.index') }}" class="menu-item {{ request()->routeIs('admin.enseignants.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>Enseignants</span>
            </a>
        
            <a href="{{ route('admin.statistiques') }}" class="menu-item {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Statistiques</span>
            </a>
        
            <a href="{{ route('admin.parametres') }}" class="menu-item {{ request()->routeIs('admin.parametres') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Paramètres</span>
            </a>
            @elseif(auth()->user()->type_utilisateur === 'enseignant')
            <!-- Menu Enseignant -->
            <a href="{{ route('enseignant.dashboard') }}" class="menu-item {{ request()->routeIs('enseignant.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Tableau de bord</span>
            </a>
        
            <a href="{{ route('enseignant.notes.create') }}" class="menu-item {{ request()->routeIs('enseignant.notes.create') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i>
                <span>Saisir notes</span>
            </a>
        
            <a href="{{ route('enseignant.classes') }}" class="menu-item {{ request()->routeIs('enseignant.classes') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Mes classes</span>
            </a>
        
            <a href="{{ route('enseignant.notes.index') }}" class="menu-item {{ request()->routeIs('enseignant.notes.index') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                <span>Notes saisies</span>
            </a>
        
            <a href="{{ route('profil.index') }}" class="menu-item {{ request()->routeIs('profil.index') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Paramètres</span>
            </a>
        @elseif(auth()->user()->type_utilisateur === 'etudiant')
            <!-- Menu Étudiant -->
            <a href="{{ route('etudiant.dashboard') }}" class="menu-item {{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i>
                <span>Tableau de bord</span>
            </a>
        
            <a href="#" class="menu-item">
                <i class="bi bi-journal-text"></i>
                <span>Mes notes</span>
            </a>
        
            <a href="#" class="menu-item">
                <i class="bi bi-calendar3"></i>
                <span>Emploi du temps</span>
            </a>
        
            <a href="{{ route('profil.index') }}" class="menu-item {{ request()->routeIs('profil.index') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Paramètres</span>
            </a>
        @endif
        </nav>


        <div class="position-absolute bottom-0 w-100 p-3" style="border-top: 1px solid #e5e7eb;">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-circle-fill text-{{ auth()->user()->type_utilisateur == 'administrateur' ? 'danger' : (auth()->user()->type_utilisateur == 'enseignant' ? 'warning' : 'success') }}" style="font-size: 8px;"></i>
                <span class="fw-medium">{{ ucfirst(auth()->user()->type_utilisateur) }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                </button>
            </form>
        </div>        
        
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Rechercher...">
            </div>
    
            <div class="d-flex align-items-center gap-3">
                <div class="notification-badge">
                    <i class="bi bi-bell fs-5 text-secondary"></i>
                    <span class="badge">3</span>
                </div>
        
                <!-- Dropdown User Profile avec Déconnexion -->
                <div class="dropdown">
                    <button class="user-profile btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->profil()->prenom ?? 'U', 0, 1)) }}
                        </div>
                        <div class="d-none d-md-block">
                            <div class="fw-semibold" style="font-size: 0.9rem;">
                            {{ auth()->user()->profil()->nom_complet ?? 'Utilisateur' }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                            {{ ucfirst(auth()->user()->type_utilisateur) }}
                            </div>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-2"></i>
                    </button>
            
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profil.index') }}">
                                <i class="bi bi-person me-2"></i>Mon Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="bi bi-gear me-2"></i>Paramètres
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>


        <!-- Page Content -->
        <div class="p-4">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>