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
            <p><strong>Rôles demandés :</strong> <?php
                                                    $demandes = array_filter($utilisateurConnecte['roles']['demande'], fn($r) => !is_null($r) && $r !== '');
                                                    echo empty($demandes) ? "Aucun" : implode(", ", $demandes);
                                                    ?></p>
            <button class="btn btn-primary" id="edit-info">Modifier mes infos</button>
        </div>

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
</script>

<?php require_once "include/footer.inc.php"; ?>
</body>

</html>