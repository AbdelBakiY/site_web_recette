<?php
session_start();
header('Content-Type: application/json');

// Vérifier la connexion
if (!isset($_SESSION['email'])) {
    // Stocker un message d'erreur en session et rediriger
    $_SESSION['error'] = "Vous devez être connecté pour commenter.";
    header("Location: recette_details.php?id=" . urlencode($_POST['recette_id']));
    exit;
}

// Récupérer les données du formulaire
$commentText = trim($_POST['comment_text'] ?? '');
$recetteId = $_POST['recette_id'] ?? null;

// Validation
if (empty($commentText)) {
    $_SESSION['error'] = "Le commentaire ne peut pas être vide";
    header("Location: recette_details.php?id=" . urlencode($recetteId));
    exit;
}

if ($recetteId === null || !is_numeric($recetteId)) {
    $_SESSION['error'] = "ID de recette invalide";
    header("Location: recette_details.php?id=" . urlencode($recetteId));
    exit;
}

// Convertir en entier
$recetteId = (int)$recetteId;

// Charger les utilisateurs
$usersFile = '../data/utilisateurs.json';
if (!file_exists($usersFile)) {
    $_SESSION['error'] = "Fichier utilisateurs introuvable";
    header("Location: ../recette_details.php?id=" . urlencode($recetteId));
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $_SESSION['error'] = "Erreur de lecture des utilisateurs";
    header("Location: ../recette_details.php?id=" . urlencode($recetteId));
    exit;
}

$currentUser = null;
foreach ($users as $user) {
    if (isset($user['email']) && $user['email'] === $_SESSION['email']) {
        $currentUser = $user;
        break;
    }
}

if (!$currentUser) {
    $_SESSION['error'] = "Utilisateur non trouvé";
    header("Location:../recette_details.php?id=" . urlencode($recetteId));
    exit;
}

// Charger les commentaires existants
$commentsFile = __DIR__ . '/../data/comments.json';
$comments = [];

if (file_exists($commentsFile)) {
    $commentsData = file_get_contents($commentsFile);
    $comments = json_decode($commentsData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $comments = [];
    }
}

if (!isset($comments[$recetteId]) || !is_array($comments[$recetteId])) {
    $comments[$recetteId] = [];
}

// Créer le nouveau commentaire
$newComment = [
    "author" => ($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''),
    "text" => htmlspecialchars($commentText),
    "date" => date('Y-m-d H:i:s'),
    "email" => $_SESSION['email']
];

// Ajouter le commentaire
array_unshift($comments[$recetteId], $newComment);

if (file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT)) === false) {
    $_SESSION['error'] = "Erreur lors de la sauvegarde du commentaire";
    header("Location: ../recette_details.php?id=" . urlencode($recetteId));
    exit;
}

$_SESSION['success'] = "Votre commentaire a été ajouté avec succès!";
header("Location: ../recette_details.php?id=" . urlencode($recetteId));
exit;
