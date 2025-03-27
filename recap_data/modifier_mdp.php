<?php
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$ancien = $_POST['ancien'] ?? '';
$nouveau = $_POST['nouveau'] ?? '';
$email = $_SESSION['email'];

$utilisateursFile = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($utilisateursFile), true);

foreach ($utilisateurs as &$u) {
    if ($u['email'] === $email) {
        if (!password_verify($ancien, $u['mdp'])) {
            http_response_code(401);
            echo "Ancien mot de passe incorrect.";
            exit;
        }

        $u['mdp'] = password_hash($nouveau, PASSWORD_DEFAULT);
        file_put_contents($utilisateursFile, json_encode($utilisateurs, JSON_PRETTY_PRINT));
        echo "Mot de passe mis à jour.";
        exit;
    }
}

http_response_code(404);
echo "Utilisateur introuvable.";
