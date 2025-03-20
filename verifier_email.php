<?php

$email = $_POST['email'];

$users = json_decode(file_get_contents('data/utilisateurs.json'), true);

$emailExists = false;
foreach ($users as $user) {
    if ($user['email'] === $email) {
        $emailExists = true;
        break;
    }
}

// Renvoyer la réponse
echo $emailExists ? 'email_existe' : 'email_nexiste_pas';
?>