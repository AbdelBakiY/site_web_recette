<?php
// Vérification de la session admin avant tout
session_start();
$utilisateursJson = file_get_contents(__DIR__ . "/../data/utilisateurs.json");
$utilisateurs = json_decode($utilisateursJson, true);

// Vérifier si l'utilisateur connecté est admin
$email = $_SESSION['email'] ?? null;
$estAdmin = false;

foreach ($utilisateurs as $u) {
    if ($u['email'] === $email && in_array("admin", $u['roles']['attribue'])) {
        $estAdmin = true;
        break;
    }
}

if (!$estAdmin) {
    http_response_code(403);
    die("Accès refusé");
}

// Récupérer les données du formulaire
$userIndex = isset($_POST['index']) ? (int)$_POST['index'] : -1;
$role = isset($_POST['role']) ? $_POST['role'] : "";

// Vérifier les valeurs
if ($userIndex < 0 || $userIndex >= count($utilisateurs) || !in_array($role, ['chef', 'traducteur'])) {
    http_response_code(400);
    die("Données invalides");
}

// Attribuer le rôle
$roleDemandeKey = "Demande" . ucfirst($role);

// Supprimer la demande
$utilisateurs[$userIndex]['roles']['demande'] = array_filter(
    $utilisateurs[$userIndex]['roles']['demande'],
    function($item) use ($roleDemandeKey) {
        return $item !== $roleDemandeKey;
    }
);

// Ajouter le rôle attribué s'il n'existe pas déjà
if (!in_array($role, $utilisateurs[$userIndex]['roles']['attribue'])) {
    $utilisateurs[$userIndex]['roles']['attribue'][] = $role;
}

// Enregistrer les modifications
file_put_contents(__DIR__ . "/../data/utilisateurs.json", json_encode($utilisateurs, JSON_PRETTY_PRINT));

echo "Rôle attribué avec succès";
?>