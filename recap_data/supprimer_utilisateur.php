<?php
// Vérification de la session admin avant tout
session_start();
$utilisateursJson = file_get_contents(__DIR__ . "/../data/utilisateurs.json");
$utilisateurs = json_decode($utilisateursJson, true);

// Vérifier si l'utilisateur connecté est admin
$email = $_SESSION['email'] ?? null;
$estAdmin = false;
$adminIndex = -1;

foreach ($utilisateurs as $i => $u) {
    if ($u['email'] === $email) {
        $adminIndex = $i;
        if (in_array("admin", $u['roles']['attribue'])) {
            $estAdmin = true;
        }
        break;
    }
}

if (!$estAdmin) {
    http_response_code(403);
    die("Accès refusé");
}

// Récupérer l'index
$userIndex = isset($_POST['index']) ? (int)$_POST['index'] : -1;

// Vérifier les valeurs
if ($userIndex < 0 || $userIndex >= count($utilisateurs)) {
    http_response_code(400);
    die("Index utilisateur invalide");
}

// Empêcher un admin de se supprimer lui-même via cette interface
if ($userIndex === $adminIndex) {
    http_response_code(400);
    die("Vous ne pouvez pas supprimer votre propre compte via cette interface");
}

// Supprimer l'utilisateur
array_splice($utilisateurs, $userIndex, 1);

// Enregistrer les modifications
file_put_contents(__DIR__ . "/../data/utilisateurs.json", json_encode($utilisateurs, JSON_PRETTY_PRINT));

echo "Utilisateur supprimé avec succès";
?>