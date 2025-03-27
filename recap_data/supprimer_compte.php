<?php
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$email = $_SESSION['email'];
$utilisateursFile = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($utilisateursFile), true);

$nouvelleListe = array_filter($utilisateurs, fn($u) => $u['email'] !== $email);

if (count($nouvelleListe) === count($utilisateurs)) {
    http_response_code(404);
    echo "Compte non trouvé.";
    exit;
}

file_put_contents($utilisateursFile, json_encode(array_values($nouvelleListe), JSON_PRETTY_PRINT));

session_destroy();
echo "Compte supprimé avec succès.";
