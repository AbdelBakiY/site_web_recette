<?php
$titre = "Profil | MonSiteDeRecettes";
require_once "include/header.inc.php";

$utilisateurs = json_decode(file_get_contents("data/utilisateurs.json"), true);
$email = $_SESSION['email'] ?? null;
$utilisateurConnecte = null;

foreach ($utilisateurs as $u) {
    if ($u['email'] === $email) {
        $utilisateurConnecte = $u;
        break;
    }
}

if (!isset($utilisateurConnecte['roles'])) {
    $utilisateurConnecte['roles'] = ['demande' => [], 'attribue' => []];
}
?>

<main class="container mt-4">
    <h1 class="mb-4">Mon Profil</h1>

    <?php if (!$utilisateurConnecte): ?>
        <div class="alert alert-warning">Vous devez être connecté pour accéder à cette page.</div>
    <?php else: ?>
        <div class="card p-3">
            <h4><?= htmlspecialchars($utilisateurConnecte['prenom']) . " " . htmlspecialchars($utilisateurConnecte['nom']) ?></h4>
            <p><strong>Email :</strong> <?= htmlspecialchars($utilisateurConnecte['email']) ?></p>
            <p><strong>Rôles attribués :</strong> <?= empty($utilisateurConnecte['roles']['attribue']) ? "Aucun" : implode(", ", $utilisateurConnecte['roles']['attribue']) ?></p>
            <p><strong>Rôles demandés :</strong>
                <?php
                $demandes = array_filter($utilisateurConnecte['roles']['demande'], fn($r) => !is_null($r) && $r !== '');
                echo empty($demandes) ? "Aucun" : implode(", ", $demandes);
                ?>
            </p>
            <button class="btn btn-primary" id="edit-info">Modifier mes infos</button>
        </div>

        <!-- Formulaire de modification -->
        <div id="form-modif" class="mt-4" style="display:none;">
            <h5>Modifier mes informations :</h5>
            <form id="modifForm">
                <div class="mb-2">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateurConnecte['prenom']) ?>" required>
                </div>
                <div class="mb-2">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateurConnecte['nom']) ?>" required>
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateurConnecte['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-success mt-2">Sauvegarder</button>
            </form>
            <div id="messageModif" class="mt-2"></div>
        </div>
        <div id="form-mdp" class="mt-5">
            <h5>Changer mon mot de passe :</h5>
            <form id="mdpForm">
                <div class="mb-2">
                    <label>Ancien mot de passe</label>
                    <input type="password" name="ancien" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="nouveau" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning mt-2">Modifier le mot de passe</button>
            </form>
            <div id="messageMdp" class="mt-2"></div>
        </div>

        <!-- Supprimer le compte -->
        <div class="mt-5">
            <button class="btn btn-danger" id="supprimerCompte">❌ Supprimer mon compte</button>
            <div id="messageSuppression" class="mt-2"></div>
        </div>

        <!-- Demande de rôle -->
        <div id="role-actions" class="mt-4"></div>

        <?php if (in_array("admin", $utilisateurConnecte['roles']['attribue'])): ?>
            <div class="mt-5">
                <h4>Gestion des rôles utilisateurs</h4>
                <div id="admin-users"></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<script>
    $(document).ready(function() {
        <?php if ($utilisateurConnecte): ?>
            const utilisateur = <?= json_encode($utilisateurConnecte) ?>;

            // Afficher boutons de rôle
            let html = "";
            const demandes = utilisateur.roles.demande.filter(r => r);
            const attribues = utilisateur.roles.attribue;

            if (!attribues.includes("chef") && !demandes.includes("DemandeChef")) {
                html += '<button class="btn btn-outline-success me-2" onclick="demanderRole(\'DemandeChef\')">Demander Chef</button>';
            }
            if (!attribues.includes("traducteur") && !demandes.includes("DemandeTraducteur")) {
                html += '<button class="btn btn-outline-primary" onclick="demanderRole(\'DemandeTraducteur\')">Demander Traducteur</button>';
            }
            $("#role-actions").html(html);

            // Admin : affichage de la table utilisateurs
            <?php if (in_array("admin", $utilisateurConnecte['roles']['attribue'])): ?>
                $.get("data/utilisateurs.json", function(data) {
                    let table = '<table class="table table-bordered mt-3"><thead><tr><th>Nom</th><th>Email</th><th>Demandes</th><th>Attribués</th><th>Actions</th></tr></thead><tbody>';

                    data.forEach((u, i) => {
                        const demandes = u.roles.demande.filter(r => r).join(', ') || 'Aucune';
                        const attribues = u.roles.attribue.join(', ') || 'Aucun';
                        table += `<tr>
                    <td>${u.prenom} ${u.nom}</td>
                    <td>${u.email}</td>
                    <td>${demandes}</td>
                    <td>${attribues}</td>
                    <td>`;
                        if (u.roles.demande.includes("DemandeChef")) {
                            table += `<button class='btn btn-sm btn-success' onclick='attribuerRole(${i}, "chef")'>Valider Chef</button> `;
                        }
                        if (u.roles.demande.includes("DemandeTraducteur")) {
                            table += `<button class='btn btn-sm btn-primary' onclick='attribuerRole(${i}, "traducteur")'>Valider Traducteur</button>`;
                        }
                        table += `</td></tr>`;
                    });

                    table += '</tbody></table>';
                    $('#admin-users').html(table);
                });
            <?php endif; ?>
        <?php endif; ?>

        // Bouton édition infos
        $('#edit-info').on('click', function() {
            $('#form-modif').slideToggle();
        });

        // Envoi formulaire AJAX
        $('#modifForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'recap_data/modifier_infos.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#messageModif').html(`<div class="alert alert-success">${response}</div>`);
                    setTimeout(() => location.reload(), 1000);
                },
                error: function() {
                    $('#messageModif').html(`<div class="alert alert-danger">Erreur lors de la modification.</div>`);
                }
            });
        });
    });

    function demanderRole(role) {
        $.post("recap_data/update_role.php", {
            roleDemande: role
        }, function() {
            location.reload();
        });
    }

    function attribuerRole(index, role) {
        $.post("recap_data/valider_role.php", {
            index: index,
            role: role
        }, function() {
            location.reload();
        });
    }

    $('#mdpForm').on('submit', function(e) {
        e.preventDefault();
        $.post('recap_data/modifier_mdp.php', $(this).serialize())
            .done(function(res) {
                $('#messageMdp').html(`<div class="alert alert-success">${res}</div>`);
                setTimeout(() => location.reload(), 1000);
            })
            .fail(function() {
                $('#messageMdp').html(`<div class="alert alert-danger">Erreur lors de la mise à jour du mot de passe.</div>`);
            });
    });

    $('#supprimerCompte').on('click', function() {
        if (confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) {
            $.post('recap_data/supprimer_compte.php')
                .done(function(res) {
                    $('#messageSuppression').html(`<div class="alert alert-success">${res}</div>`);
                    setTimeout(() => window.location.href = 'logout.php', 1500);
                })
                .fail(function() {
                    $('#messageSuppression').html(`<div class="alert alert-danger">Erreur lors de la suppression du compte.</div>`);
                });
        }
    });
</script>

<?php require_once "include/footer.inc.php"; ?>
</body>

</html>