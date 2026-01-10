@extends('layouts.app')

@section('title', 'Aide & Support - EduNet')

@section('content')
<div class="min-vh-100 bg-light">
    <!-- Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="d-flex align-items-center gap-3 mb-3">
                <a href="{{ route('welcome') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-question-circle me-3"></i>Aide & Support
            </h1>
            <p class="lead mb-0">Trouvez rapidement des réponses à vos questions</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Navigation rapide</h6>
                        <nav class="nav flex-column gap-1">
                            <a href="#connexion" class="nav-link text-dark">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                            </a>
                            <a href="#etudiants" class="nav-link text-dark">
                                <i class="bi bi-mortarboard me-2"></i>Pour les étudiants
                            </a>
                            <a href="#enseignants" class="nav-link text-dark">
                                <i class="bi bi-person-video3 me-2"></i>Pour les enseignants
                            </a>
                            <a href="#problemes" class="nav-link text-dark">
                                <i class="bi bi-exclamation-triangle me-2"></i>Problèmes courants
                            </a>
                            <a href="#contact" class="nav-link text-dark">
                                <i class="bi bi-envelope me-2"></i>Nous contacter
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-lg-9">
                
                <!-- Connexion -->
                <section id="connexion" class="mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-box-arrow-in-right text-primary me-2"></i>
                                Comment se connecter ?
                            </h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3">
                                        <h5 class="fw-bold mb-3">1. Choisissez votre profil</h5>
                                        <p class="mb-0">Sur la page d'accueil, cliquez sur la carte correspondant à votre rôle (Étudiant, Enseignant ou Administrateur).</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3">
                                        <h5 class="fw-bold mb-3">2. Entrez vos identifiants</h5>
                                        <p class="mb-0">Saisissez votre email et votre mot de passe fournis par l'administration.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-4 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Première connexion ?</strong> Votre mot de passe temporaire vous a été envoyé par email.
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Pour les étudiants -->
                <section id="etudiants" class="mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                Guide pour les étudiants
                            </h3>
                            
                            <div class="accordion" id="accordionEtudiants">
                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                            Comment consulter mes notes ?
                                        </button>
                                    </h2>
                                    <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#accordionEtudiants">
                                        <div class="accordion-body">
                                            Une fois connecté, accédez à votre tableau de bord. Vous y trouverez :
                                            <ul class="mt-2 mb-0">
                                                <li>Vos dernières notes sur la page d'accueil</li>
                                                <li>Le menu "Mes Notes" pour l'historique complet</li>
                                                <li>Le menu "Bulletin" pour votre relevé de notes</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                            Comment télécharger mon bulletin ?
                                        </button>
                                    </h2>
                                    <div id="q2" class="accordion-collapse collapse" data-bs-parent="#accordionEtudiants">
                                        <div class="accordion-body">
                                            Allez dans le menu <strong>"Bulletin"</strong> puis cliquez sur le bouton <strong>"Télécharger PDF"</strong> en haut à droite.
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                            Comment consulter mes matières ?
                                        </button>
                                    </h2>
                                    <div id="q3" class="accordion-collapse collapse" data-bs-parent="#accordionEtudiants">
                                        <div class="accordion-body">
                                            Dans le menu <strong>"Mes Matières"</strong>, vous trouverez la liste complète de vos matières avec leurs coefficients, crédits ECTS et vos moyennes actuelles.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Pour les enseignants -->
                <section id="enseignants" class="mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-person-video3 text-warning me-2"></i>
                                Guide pour les enseignants
                            </h3>
                            
                            <div class="accordion" id="accordionEnseignants">
                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#e1">
                                            Comment saisir les notes ?
                                        </button>
                                    </h2>
                                    <div id="e1" class="accordion-collapse collapse show" data-bs-parent="#accordionEnseignants">
                                        <div class="accordion-body">
                                            <p>Deux méthodes sont disponibles :</p>
                                            <ol>
                                                <li><strong>Saisie individuelle :</strong> Menu "Notes" → "Ajouter une note"</li>
                                                <li><strong>Saisie groupée (recommandé) :</strong> Menu "Notes" → "Saisie groupée" pour saisir toute une classe en une fois</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#e2">
                                            Puis-je modifier une note déjà saisie ?
                                        </button>
                                    </h2>
                                    <div id="e2" class="accordion-collapse collapse" data-bs-parent="#accordionEnseignants">
                                        <div class="accordion-body">
                                            Oui, dans le menu <strong>"Notes"</strong>, cliquez sur l'icône crayon (modifier) à côté de la note concernée.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Problèmes courants -->
                <section id="problemes" class="mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                Problèmes courants
                            </h3>
                            
                            <div class="alert alert-warning">
                                <h5 class="fw-bold">Mot de passe oublié ?</h5>
                                <p class="mb-2">Cliquez sur "Mot de passe oublié" sur la page de connexion et suivez les instructions.</p>
                            </div>

                            <div class="alert alert-info">
                                <h5 class="fw-bold">Je ne peux pas me connecter</h5>
                                <ul class="mb-0">
                                    <li>Vérifiez que vous avez sélectionné le bon profil (Étudiant/Enseignant/Admin)</li>
                                    <li>Assurez-vous d'utiliser le bon email</li>
                                    <li>Vérifiez que votre compte a bien été créé par l'administration</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section id="contact" class="mb-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="fw-bold mb-4">
                                <i class="bi bi-envelope text-primary me-2"></i>
                                Besoin d'aide supplémentaire ?
                            </h3>
                            <p>Si vous ne trouvez pas de réponse à votre question, n'hésitez pas à nous contacter :</p>
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3 text-center">
                                        <i class="bi bi-envelope-fill text-primary fs-1 mb-3"></i>
                                        <h5 class="fw-bold">Par Email</h5>
                                        <p class="text-muted mb-2">support@edunet.com</p>
                                        <a href="{{ route('info.contact') }}" class="btn btn-primary btn-sm">
                                            Formulaire de contact
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bg-light p-4 rounded-3 text-center">
                                        <i class="bi bi-telephone-fill text-success fs-1 mb-3"></i>
                                        <h5 class="fw-bold">Par Téléphone</h5>
                                        <p class="text-muted mb-2">+228 92 99 86 81</p>
                                        <p class="small text-muted mb-0">Lun - Ven : 8h - 17h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection