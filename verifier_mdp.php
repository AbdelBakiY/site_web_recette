<?php
$email = $_POST['email'];
$mdp = $_POST['mdp'];

$utilisateursFile = 'data/utilisateurs.json';
if (file_exists($utilisateursFile)) {
    $utilisateursData = json_decode(file_get_contents($utilisateursFile), true);
    if (!is_array($utilisateursData)) {
        $utilisateursData = [];
    }
} else {
    $utilisateursData = [];
}

$mdpCorrect = false;
foreach ($utilisateursData as $utilisateur) {
    if ($utilisateur['email'] === $email && password_verify($mdp, $utilisateur['mdp'])) {
        $mdpCorrect = true;
        break;
    }
}

echo $mdpCorrect ? 'mdp_correct' : 'mdp_incorrect';
?>