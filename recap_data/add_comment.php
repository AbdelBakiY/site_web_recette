<?php
session_start();
header('Content-Type: application/json');

// Vérifier la connexion
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'error' => 'Connectez-vous pour commenter']);
    exit;
}

// Récupération et validation des données
$commentText = trim($_POST['comment_text'] ?? '');
$recetteId = (int)($_POST['recette_id'] ?? 0);

if (empty($commentText)) {
    echo json_encode(['success' => false, 'error' => 'Le commentaire ne peut pas être vide']);
    exit;
}

// Chargement des utilisateurs
$usersFile = '../data/utilisateurs.json';
if (!file_exists($usersFile)) {
    echo json_encode(['success' => false, 'error' => 'Fichier utilisateurs introuvable']);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Erreur de lecture des utilisateurs']);
    exit;
}

// Trouver l'utilisateur actuel
$currentUser = null;
foreach ($users as $user) {
    if (isset($user['email']) && $user['email'] === $_SESSION['email']) {
        $currentUser = $user;
        break;
    }
}

if (!$currentUser) {
    echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);
    exit;
}

// Chargement des commentaires
$commentsFile = '../data/comments.json';
$comments = [];

if (file_exists($commentsFile)) {
    $comments = json_decode(file_get_contents($commentsFile), true) ?? [];
}

if (!isset($comments[$recetteId])) {
    $comments[$recetteId] = [];
}

// Ajout du nouveau commentaire
$newComment = [
    "author" => ($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''),
    "text" => htmlspecialchars($commentText),
    "date" => date('Y-m-d H:i:s'),
    "email" => $_SESSION['email']
];

array_unshift($comments[$recetteId], $newComment);

// Sauvegarde
if (file_put_contents($commentsFile, json_encode($comments, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'comment' => $newComment,
        'total_comments' => count($comments[$recetteId])
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur de sauvegarde']);
}