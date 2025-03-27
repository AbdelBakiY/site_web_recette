<?php
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$ancienEmail = $_SESSION['email'];
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';

$utilisateursFile = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($utilisateursFile), true);

foreach ($utilisateurs as &$u) {
    if ($u['email'] === $ancienEmail) {
        $u['nom'] = $nom;
        $u['prenom'] = $prenom;
        $u['email'] = $email;

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;

        break;
    }
}

file_put_contents($utilisateursFile, json_encode($utilisateurs, JSON_PRETTY_PRINT));
echo "Informations mises à jour avec succès.";
