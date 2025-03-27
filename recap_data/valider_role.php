<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_POST['index']) || !isset($_POST['role'])) {
    http_response_code(400);
    exit("Requête invalide");
}

$adminEmail = $_SESSION['email'];
$index = intval($_POST['index']);
$role = $_POST['role'];
$file = '../data/utilisateurs.json';

$utilisateurs = json_decode(file_get_contents($file), true);

// Vérification que l'utilisateur est bien admin
$isAdmin = false;
foreach ($utilisateurs as $adminUser) {
    if ($adminUser['email'] === $adminEmail && in_array("admin", $adminUser['roles']['attribue'])) {
        $isAdmin = true;
        break;
    }
}

if (!$isAdmin) {
    http_response_code(403);
    exit("Non autorisé");
}

if (isset($utilisateurs[$index])) {
    $utilisateurs[$index]['roles']['attribue'][] = $role;
    $utilisateurs[$index]['roles']['attribue'] = array_unique($utilisateurs[$index]['roles']['attribue']);
    $utilisateurs[$index]['roles']['demande'] = array_filter(
        $utilisateurs[$index]['roles']['demande'],
        fn($r) => $r !== "Demande" . ucfirst($role)
    );
    file_put_contents($file, json_encode($utilisateurs, JSON_PRETTY_PRINT));
    echo "Rôle attribué avec succès";
} else {
    http_response_code(404);
    echo "Utilisateur non trouvé";
}
