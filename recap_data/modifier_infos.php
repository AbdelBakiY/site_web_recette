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
$ancienMdp = $_POST['ancien_mdp'] ?? '';
$nouveauMdp = $_POST['nouveau_mdp'] ?? '';

$utilisateursFile = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($utilisateursFile), true);
$trouve = false;

foreach ($utilisateurs as &$u) {
    if ($u['email'] === $ancienEmail) {
        // Met à jour les infos
        $u['nom'] = $nom;
        $u['prenom'] = $prenom;
        $u['email'] = $email;

        // Si changement de mot de passe demandé
        if (!empty($ancienMdp) || !empty($nouveauMdp)) {
            if (empty($ancienMdp) || empty($nouveauMdp)) {
                http_response_code(400);
                echo "Les deux champs mot de passe sont requis.";
                exit;
            }

            if (!password_verify($ancienMdp, $u['mdp'])) {
                http_response_code(401);
                echo "Ancien mot de passe incorrect.";
                exit;
            }

            $u['mdp'] = password_hash($nouveauMdp, PASSWORD_DEFAULT);
        }

        // Met à jour la session
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $trouve = true;
        break;
    }
}

if ($trouve) {
    file_put_contents($utilisateursFile, json_encode($utilisateurs, JSON_PRETTY_PRINT));
    echo "Informations mises à jour avec succès.";
} else {
    http_response_code(404);
    echo "Utilisateur non trouvé.";
}
