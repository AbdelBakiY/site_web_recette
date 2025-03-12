<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site de Recettes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css"> <!-- Ton fichier CSS si besoin -->
</head>
<body>

<!-- Navbar Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍽️ MonSiteDeRecettes</a>

        <!-- Bouton pour mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu de navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="recettes.php">Recettes</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Catégories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="categorie.php?type=entrees">Entrées</a></li>
                        <li><a class="dropdown-item" href="categorie.php?type=plats">Plats</a></li>
                        <li><a class="dropdown-item" href="categorie.php?type=desserts">Desserts</a></li>
                        <li><a class="dropdown-item" href="categorie.php?type=boissons">Boissons</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="blog.php">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>

            <!-- Barre de recherche -->
            <form class="d-flex ms-3" action="recherche.php" method="GET">
                <input class="form-control me-2" type="search" name="q" placeholder="Rechercher..." aria-label="Search">
                <button class="btn btn-outline-success" type="submit">🔍</button>
            </form>

            <!-- Bouton Connexion / Inscription -->
            <ul class="navbar-nav ms-3">
                <li class="nav-item">
                    <a class="btn btn-primary" href="login.php">Connexion</a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-primary" href="register.php">Inscription</a>
                </li>
            </ul>
        </div>
    </div>
</nav>