<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_POST['roleDemande'])) {
    http_response_code(400);
    exit("Requête invalide");
}

$email = $_SESSION['email'];
$roleDemande = $_POST['roleDemande'];
$file = '../data/utilisateurs.json';

$utilisateurs = json_decode(file_get_contents($file), true);
foreach ($utilisateurs as &$user) {
    if ($user['email'] === $email) {
        if (!in_array($roleDemande, $user['roles']['demande'])) {
            $user['roles']['demande'][] = $roleDemande;
        }
        break;
    }
}

file_put_contents($file, json_encode($utilisateurs, JSON_PRETTY_PRINT));
echo "Role demandé avec succès";
