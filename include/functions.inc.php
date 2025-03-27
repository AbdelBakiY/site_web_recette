<?php

function connexion($e_mail, $mdp)
{

    $utilisateursFile = 'data/utilisateurs.json';

    if (!file_exists($utilisateursFile)) {
        return "Erreur : Fichier des utilisateurs introuvable.";
    }

    $utilisateursData = json_decode(file_get_contents($utilisateursFile), true);
    if (!is_array($utilisateursData)) {
        return "Erreur : Fichier des utilisateurs corrompu.";
    }


    foreach ($utilisateursData as $utilisateur) {
        if ($utilisateur['email'] === $e_mail) {
            
            if (password_verify($mdp, $utilisateur['mdp'])) {
                
<<<<<<< HEAD
                session_start();
=======

>>>>>>> 3e506486891cf90240861b54ad3d9d65915d061d
                
                $_SESSION['nom'] = $utilisateur['nom'];
                $_SESSION['prenom'] = $utilisateur['prenom'];
                $_SESSION['email'] = $utilisateur['email'];
                $_SESSION['roles'] = $utilisateur['roles']; 


                header("Location: index.php");
                exit();
            } else {
                return "Mot de passe incorrect.";
            }
        }
    }

    return "Cet email n'existe pas.";
}


function inscription($nom, $prenom, $email, $mdp, $choixRole)
{
    $utilisateursFile = 'data/utilisateurs.json';
    $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
    if($email==="abdelbaki.mougari@gmail.com"){

        $nouvelUtilisateur = array(
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "mdp" => $mdpHash,
            "roles" => array(
                "demande" => $choixRole ? array($choixRole) : array(),

                "attribue" => array("admin")
            )
        );
    }else{
        $nouvelUtilisateur = array(
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "mdp" => $mdpHash,
            "roles" => array(
                "demande" => $choixRole ? array($choixRole) : array(),
                "attribue" => array()
            )
        );
    };

    if (file_exists($utilisateursFile)) {
        $utilisateursData = json_decode(file_get_contents($utilisateursFile), true);
        if (!is_array($utilisateursData)) {
            $utilisateursData = [];
        }
    } else {
        $utilisateursData = [];
    }

    $utilisateursData[] = $nouvelUtilisateur;

    if (file_put_contents($utilisateursFile, json_encode($utilisateursData, JSON_PRETTY_PRINT))) {
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['email'] = $email;
        $_SESSION['roles'] = $nouvelUtilisateur['roles'];


        header("Location: index.php");
        exit();
    } else {
        return "Erreur lors de l'enregistrement de l'utilisateur.";
    }
}

function envoyerLienReinitialisation($email) {
    $utilisateursFile = 'data/utilisateurs.json';

    if (!file_exists($utilisateursFile)) {
        return "Erreur : Fichier des utilisateurs introuvable.";
    }

    $utilisateursData = json_decode(file_get_contents($utilisateursFile), true);
    if (!is_array($utilisateursData)) {
        return "Erreur : Fichier des utilisateurs corrompu.";
    }

    // Vérifier si l'e-mail existe
    $utilisateurTrouve = null;
    foreach ($utilisateursData as $utilisateur) {
        if ($utilisateur['email'] === $email) {
            $utilisateurTrouve = $utilisateur;
            break;
        }
    }

    if (!$utilisateurTrouve) {
        return "Cet email n'existe pas.";
    }

    $lienReinitialisation = "http://http://abdel.alwaysdata.net/site_web_recette/reinitialiser_mdp.php?email=" . urlencode($email);

    $sujet = "Réinitialisation de votre mot de passe";
    $message = "Bonjour " . $utilisateurTrouve['prenom'] . ",\n\n";
    $message .= "Cliquez sur ce lien pour réinitialiser votre mot de passe :\n";
    $message .= $lienReinitialisation . "\n\n";
    $message .= "Si vous n'avez pas demandé cette réinitialisation, ignorez cet e-mail.\n";

    $headers = "From: smtp-abdel.alwaysdata.net";

    if (mail($email, $sujet, $message, $headers)) {
        return "Un lien de réinitialisation a été envoyé à votre adresse e-mail.";
    } else {
        return "Erreur lors de l'envoi de l'e-mail.";
    }
}

function reinitialiserMotDePasse($email, $nouveauMdp) {
    $utilisateursFile = 'data/utilisateurs.json';

 

    $utilisateursData = json_decode(file_get_contents($utilisateursFile), true);
 
    foreach ($utilisateursData as &$utilisateur) {
        if ($utilisateur['email'] === $email) {
            $utilisateur['mdp'] = password_hash($nouveauMdp, PASSWORD_DEFAULT);

            file_put_contents($utilisateursFile, json_encode($utilisateursData, JSON_PRETTY_PRINT));

            return "mdp renitialisé";
        }
    }

    return "mail invalide.";
}

?>