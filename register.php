<?php
$titre = "Inscription | MonSiteDeRecettes";
require_once("include/header.inc.php");

if (isset($_POST["inscription"])) {
    $Nom = $_POST["Nom"];
    $Prenom = $_POST["Prenom"];
    $e_mail = $_POST["e-mail"];
    $mdp = $_POST["mdp"];
    $Cmdp = $_POST["Cmdp"];

    $choixRole = isset($_POST['choix']) ? $_POST['choix'] : null;
    inscription($Nom, $Prenom, $e_mail, $mdp, $choixRole);
}





// traitemnt de comfirmation de mdp 

?>
<script>
    $(document).ready(function() {
        $('#mdp, #Cmdp').on('input', function() {
            var mdp = $('#mdp').val().trim();
            var Cmdp = $('#Cmdp').val().trim();

            if (mdp !== '' && Cmdp !== '') {
                if (mdp === Cmdp) {
                    $('#mdp_message').text("Les mots de passe correspondent.")
                        .removeClass()
                        .addClass("success");
                } else {
                    $('#mdp_message').text("Les mots de passe ne correspondent pas.")
                        .removeClass()
                        .addClass("error");
                }
            } else {
                $('#mdp_message').text("").removeClass();
            }
        });



        function verifierEmail(email) {
            $.ajax({
                url: 'verifier_email.php',
                type: 'POST',
                data: {
                    email: email
                },
                success: function(response) {
                    console.log(response);
                    if (response.trim() === 'email_existe') {
                        $('#deja_mail').removeClass('hidden').addClass('visible');
                        emailExists = true;
                    } else {
                        $('#deja_mail').removeClass('visible').addClass('hidden');
                        emailExists = false;
                    }
                },
                error: function() {
                    alert('Erreur lors de la vérification de l\'email.');
                }
            });
        }
        $('#e-mail').on('input', function() {
            var email = $(this).val();
            verifierEmail(email);
        });



        $('form').on('submit', function(event) {
            var mdp = $('#mdp').val().trim();
            var Cmdp = $('#Cmdp').val().trim();

            if (mdp !== Cmdp) {
                event.preventDefault();
                $('#non_cor').show();
            } else {
                $('#non_cor').hide();

            }
            if (emailExists) {
                event.preventDefault();
                $('#deja_mail').removeClass('hidden').addClass('visible');
            }

        });
    });
</script>

<style>
    .form-container-inscription {
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

    #mdp_message {
        font-size: 14px;
        color: red;
        margin-top: 5px;
    }

    /* Style des boutons radio */
    .radio-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
        text-align: left;
    }

    .radio-option {
        display: flex;
        align-items: center;
        font-size: 16px;
        cursor: pointer;
        gap: 8px;
    }

    .radio-option input {
        display: none;
    }

    .custom-radio {
        width: 18px;
        height: 18px;
        border: 2px solid #007bff;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    .radio-option input:checked+.custom-radio::after {
        content: "";
        width: 10px;
        height: 10px;
        background: #007bff;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
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

    /* Messages d'erreur */
    #non_cor,
    #deja_mail {
        display: none;
        color: red;
        text-align: center;
        margin-top: 5px;
    }

    #deja_mail.visible {
        display: block;
    }

    #deja_mail.hidden {
        display: none;
    }



    #mdp_message.success {
        color: green;
    }

    #mdp_message.error {
        color: red;
    }
</style>

<main>
    <h2 style="color:black;text-align:center;font-size: 2em;font-family: 'Poppins', sans-serif;">Inscription</h2>

    <form action="register.php" method="post" class="form-container-inscription">
        <label for="Nom" class="form-label">Nom :</label>
        <input type="text" id="Nom" name="Nom" required class="form-input" />

        <label for="Prenom" class="form-label">Prénom :</label>
        <input type="text" id="Prenom" name="Prenom" required class="form-input" />

        <label for="e-mail" class="form-label">E-mail :</label>
        <input type="email" id="e-mail" name="e-mail" required class="form-input" />


        <label for="mdp" class="form-label">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" required class="form-input" />
        <div id="mdp_message"></div>

        <label for="Cmdp" class="form-label">Confirmez votre mot de passe :</label>
        <input type="password" id="Cmdp" name="Cmdp" required class="form-input" />

        <span style="color:red;display:none;margin-bottom:2%;text-align:center;" id="non_cor">Les deux mots de passe ne correspondent pas.</span>
        <span style="color:red;margin-bottom:2%;text-align:center;" id="deja_mail" class="hidden">Un compte avec cet e-mail existe déjà.</span>
        <label>Demandez un rôle (Optionnel) :</label>
        <div class="radio-container">
            <label class="radio-option">
                <input type="radio" name="choix" value="DemandeChef">
                <span class="custom-radio"></span> Demande Chef
            </label>
            <label class="radio-option">
                <input type="radio" name="choix" value="DemandeTraducteur">
                <span class="custom-radio"></span> Demande Traducteur
            </label>
        </div>
        <input type="submit" value="Inscrivez-vous" class="form-submit" name="inscription" />

        <a href="login.php" class="form-link">Connexion</a>
    </form>
</main>

<?php
require_once 'include/footer.inc.php';
?>