<?php
$titre = "Connexion | MonSiteDeRecettes";
include 'include/header.inc.php';

if (isset($_POST['connexion'])) {
    $e_mail = htmlspecialchars($_POST['e-mail']);
    $mdp = htmlspecialchars($_POST['mdp']);

    connexion($e_mail, $mdp);

}$_SESSION['roles'] = $user['roles']; 


?>

<script>
    $(document).ready(function() {
        let emailExists = false;
        let mdpCorrect = false;
        $('#e-mail').on('input', function() {
            var email = $(this).val().trim();
            if (email !== '') {
                $.ajax({
                    url: 'verifier_email.php',
                    type: 'POST',
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.trim() === 'email_existe') {
                            $('#mail_faux').hide();
                            emailExists = true;
                        } else {
                            $('#mail_faux').show();
                            emailExists = false;
                        }
                        verifierBoutonSubmit();
                    },
                    error: function() {
                        alert('Erreur lors de la vérification de l\'email.');
                    }
                });
            } else {
                $('#mail_faux').hide();
                emailExists = false;
                verifierBoutonSubmit();
            }
        });

        $('#mdp').on('input', function() {
            var email = $('#e-mail').val().trim();
            var mdp = $(this).val().trim();
            if (email !== '' && mdp !== '') {
                $.ajax({
                    url: 'verifier_mdp.php',
                    type: 'POST',
                    data: {
                        email: email,
                        mdp: mdp
                    },
                    success: function(response) {
                        if (response.trim() === 'mdp_correct') {
                            $('#mdp_faux').hide();
                            mdpCorrect = true;
                        } else {
                            $('#mdp_faux').show();
                            mdpCorrect = false;
                        }
                        verifierBoutonSubmit();
                    },
                    error: function() {
                        alert('Erreur lors de la vérification du mot de passe.');
                    }
                });
            } else {
                $('#mdp_faux').hide();
                mdpCorrect = false;
                verifierBoutonSubmit();
            }
        });

        function verifierBoutonSubmit() {
            if (emailExists && mdpCorrect) {
                $('input[name="connexion"]').prop('disabled', false);
            } else {
                $('input[name="connexion"]').prop('disabled', true);
            }
        }

        verifierBoutonSubmit();
    });
</script>
</script>
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

    /* Style du bouton de connexion */
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

    /* Messages d'erreur */
    #mail_faux,
    #mdp_faux {
        display: none;
        color: red;
        text-align: center;
        margin-top: 5px;
    }
</style>

<main>
    <h2 style="color:black;text-align:center;font-size: 2em;font-family: 'Poppins', sans-serif;">Connexion</h2>
    <form action="login.php" method="post" class="form-container">
        <label for="e-mail" class="form-label">E-mail :</label>
        <input type="email" id="e-mail" name="e-mail" required class="form-input" />

        <label for="mdp" class="form-label">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required class="form-input" />
        <a href="mdp_oublie.php" class="form-link" style="margin-bottom:2%;text-align:center;">Mot de passe oublié ?</a>
        <span style="color:red;display:none;margin-bottom:2%;text-align:center;" id="mail_faux">Cet email n'existe pas</span>
        <span style="color:red;display:none;margin-bottom:2%;text-align:center;" id="mdp_faux">Mot de passe incorrect</span>

        <input type="submit" value="Connexion" class="form-submit" name="connexion" />

        <a href="register.php" class="form-link">Inscrivez-vous</a>
    </form>
</main>
<?php include 'include/footer.inc.php'; ?>