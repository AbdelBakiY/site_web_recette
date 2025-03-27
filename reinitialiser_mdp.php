<?php
$titre = "Réinitialisation du mot de passe | MonSiteDeRecettes";

include 'include/headerinc.php';

if (isset($_POST['reinitialiser_mdp'])) {
    $email = $_POST['email'];
    
    $nouveauMdp = htmlspecialchars($_POST['nouveau_mdp']);
    $resultat = reinitialiserMotDePasse($email, $nouveauMdp);
    
    header("Location: login.php");
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

    .message {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }

    .message.success {
        color: green;
    }

    .message.error {
        color: red;
    }
</style>

<main>
    <h2 style="color:black;text-align:center;font-size: 2em;font-family: 'Poppins', sans-serif;">Réinitialisation du mot de passe</h2>
    <form action="reinitialiser_mdp.php" method="post" class="form-container">
        <input type="hidden" name="email" value="<?php if(isset($_GET["email"])){
            echo htmlspecialchars($_GET["email"]) ;

        }else{
            header("location : mdp_oublie") ; 
        }
        
        ?>" />

        <label for="nouveau_mdp" class="form-label">Nouveau mot de passe :</label>
        <input type="password" id="nouveau_mdp" name="nouveau_mdp" required class="form-input" />

        <input type="submit" value="Réinitialiser le mot de passe" class="form-submit" name="reinitialiser_mdp" />
    </form>
</main>

<?php include 'include/footer.inc.php'; ?>