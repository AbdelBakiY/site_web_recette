<?php
<<<<<<< HEAD
include 'include/functions.inc.php';

session_start();
$Logged = isset($_SESSION['email']);
=======
include 'functions.inc.php';
 session_start(); 
 $Logged=isset($_SESSION['email']) ;
>>>>>>> 3e506486891cf90240861b54ad3d9d65915d061d
?>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title><?php echo $titre; ?></title>
    <!-- Bootstrap CSS -->
=======
    <title><?php echo $titre;?></title>
>>>>>>> 3e506486891cf90240861b54ad3d9d65915d061d
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

<<<<<<< HEAD
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">🍽️ MonSiteDeRecettes</a>
=======
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍽️ FoodMa</a>
>>>>>>> 3e506486891cf90240861b54ad3d9d65915d061d

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

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
                </ul>

                <form class="d-flex ms-3" action="recherche.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Rechercher..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">🔍</button>
                </form>

<<<<<<< HEAD
                <ul class="navbar-nav ms-3">
                    <li>
                        <?php if ($Logged): ?>
                            <a href="profil.php" class="nav-link"> <img src="" alt="Avatar" class="avatar">
                            </a>
                            <a href="logout.php" id="login-btn" class="btn btn-danger">Se déconnecter</a>
                        <?php else: ?>
                            <a href="login.php" id="login-btn" class="btn btn-primary">Se connecter</a>
                        <?php endif; ?>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
=======
            <ul class="navbar-nav ms-3">
                <?php if ($Logged): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> Mon compte
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" id="login-btn" class="btn btn-primary">Se connecter</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
>>>>>>> 3e506486891cf90240861b54ad3d9d65915d061d
