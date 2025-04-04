<?php
session_start();
header('Content-Type: application/json');

// Debug - À enlever en production
error_log("Début du script like_recette.php");
error_log("Session: " . print_r($_SESSION, true));
error_log("POST: " . print_r($_POST, true));

if (!isset($_SESSION['email'])) {
    error_log("Utilisateur non connecté");
    echo json_encode(['success' => false, 'error' => 'Connectez-vous pour aimer']);
    exit;
}

// Utiliser le bon nom de paramètre (recette_id)
$recetteId = $_POST['recette_id'] ?? null;
error_log("ID reçu: " . $recetteId);

if ($recetteId === null || !is_numeric($recetteId)) {
    error_log("ID recette invalide: " . $recetteId);
    echo json_encode(['success' => false, 'error' => 'ID recette invalide']);
    exit;
}

$usersFile = __DIR__ . '/../data/utilisateurs.json';
if (!file_exists($usersFile)) {
    error_log("Fichier utilisateurs manquant");
    echo json_encode(['success' => false, 'error' => 'Fichier utilisateurs manquant']);
    exit;
}

$users = json_decode(file_get_contents($usersFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Erreur de lecture du fichier utilisateurs");
    echo json_encode(['success' => false, 'error' => 'Erreur de lecture des utilisateurs']);
    exit;
}

error_log("Nombre d'utilisateurs: " . count($users));

foreach ($users as &$user) {
    if ($user['email'] === $_SESSION['email']) {
        error_log("Utilisateur trouvé: " . $user['email']);
        
        $user['likes'] = $user['likes'] ?? [];
        error_log("Likes actuels: " . print_r($user['likes'], true));

        $key = array_search($recetteId, $user['likes']);
        if ($key !== false) {
            array_splice($user['likes'], $key, 1);
            $liked = false;
            error_log("Like retiré");
        } else {
            $user['likes'][] = (int)$recetteId;
            $liked = true;
            error_log("Like ajouté");
        }

        // Calculer le total des likes
        $totalLikes = 0;
        foreach ($users as $u) {
            if (isset($u['likes']) && in_array($recetteId, $u['likes'])) {
                $totalLikes++;
            }
        }
        error_log("Total likes: " . $totalLikes);

        if (file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT))) {
            error_log("Sauvegarde réussie");
            echo json_encode([
                'success' => true,
                'liked' => $liked,
                'new_likes' => $totalLikes
            ]);
            
        } else {
            error_log("Échec de la sauvegarde");
            echo json_encode(['success' => false, 'error' => 'Erreur de sauvegarde']);
        }
        exit;
    }
}

error_log("Utilisateur non trouvé dans le fichier");
echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);