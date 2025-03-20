<?php
$titre = "Mot de passe oublié | MonSiteDeRecettes";

include 'include/header.inc.php';

if (isset($_POST['mdp_oublie'])) {
    $email = urldecode( htmlspecialchars($_POST['email']));
    $resultat = envoyerLienReinitialisation($email);
    echo "<p style='color:green;'>$resultat</p>"; 
    
    
}
?>

<style>
    .form-container {
        background: #fff;
        max-width: 400px;
        margin: 40px auto;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        text-align: center;
    }

    .form-label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 5px;
        text-align: left;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
        font-size: 16px;
        transition: 0.3s;
        background: #fff;
        color: #333;
    }

    .form-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    .form-submit {
        width: 100%;
        padding: 12px;
        background: #007bff;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: bold;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .form-submit:hover {
        background: #0056b3;
    }

    .form-link {
        display: block;
        text-align: center;
        margin-top: 15px;
        text-decoration: none;
        font-size: 14px;
        font-weight: bold;
        color: #007bff;
        transition: 0.3s;
    }

    .form-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }
</style>

<main>
    <h2 style="color:black;text-align:center;font-size: 2em;font-family: 'Poppins', sans-serif;">Mot de passe oublié</h2>
    <form action="mdp_oublie.php" method="post" class="form-container">
        <label for="email" class="form-label">E-mail :</label>
        <input type="email" id="email" name="email" required class="form-input" />

        <input type="submit" value="Envoyer le lien de réinitialisation" class="form-submit" name="mdp_oublie" />

        <a href="login.php" class="form-link">Retour à la connexion</a>
    </form>
</main>

<?php include 'include/footer.inc.php'; ?>