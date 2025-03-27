<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_POST['index'])) {
    http_response_code(403);
    exit("Requête invalide.");
}

$index = intval($_POST['index']);
$adminEmail = $_SESSION['email'];

$utilisateursFile = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($utilisateursFile), true);

if ($utilisateurs[$index]['email'] === $adminEmail) {
    http_response_code(403);
    echo "Impossible de vous supprimer vous-même.";
    exit;
}

unset($utilisateurs[$index]);

file_put_contents($utilisateursFile, json_encode(array_values($utilisateurs), JSON_PRETTY_PRINT));

echo "Utilisateur supprimé.";
