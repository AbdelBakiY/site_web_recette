<?php

function connexion($e_mail, $mdp) {}

function inscription($nom, $prenom, $email, $mdp,$choixRole)
{


    $utilisateursFile = 'data/utilisateurs.json';
    $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

    $nouvelUtilisateur = array(
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "mdp" => $mdpHash,
        "roles" => array(
            "demande" => array($choixRole),
            "attribue" => array()
        )
    );

    $utilisateursData[] = $nouvelUtilisateur;

    if (file_put_contents($utilisateursFile, json_encode($utilisateursData, JSON_PRETTY_PRINT))) {
        session_start();
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        
        header("Location: index.php");
        exit();

    } else {
        return "Erreur lors de l'enregistrement de l'utilisateur.";
    }
    
    
}

?>