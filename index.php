<?php
$titre = "Recettes | MonSiteDeRecettes";
include 'include/header.inc.php'; ?>

<script>
    $(document).ready(function() {
        $.ajax({
            url: "recap_data/getRecette.php",
            method: "GET",
            dataType: "json"
        }).done(function(data) {
            let container = $("#recettes-container");
            container.empty();

            data.forEach(recette => {

                let image = recette.imageURL || "default.jpg";
                let titre = recette.nameFR || recette.name || "Titre inconnu";
                let description = recette.name || "Pas de description";
                let steps = recette.stepsFR || recette.steps || [];
                let author = recette.author;
                let card = `
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="${image}" class="card-img-top" alt="${titre}">
                        <div class="card-body">
                            <h5 class="card-title">${titre}</h5>
                            <p class="card-text">${description}</p>
                        </div>
                        <div class="author mt-3">
                         <small class="text-muted">Auteur : ${recette.Author || 'Inconnu'}</small>
                        </div>
                        <ul class="list-group list-group-flush">
                            ${steps.map(step => `<li class="list-group-item">${step}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
                container.append(card);
            });
        }).fail(function() {
            console.log("Erreur lors du chargement des recettes.");
        });


        $("#searchUser").on("click", function() {
            let prenom = $("#prenomInput").val();
            $.ajax({
                url: "get_utilisateur.php",
                method: "GET",
                data: {
                    prenom: prenom
                },
                dataType: "json"
            }).done(function(user) {
                let nom = user.nom || user.lastname || "Nom inconnu";
                let email = user.email || user.mail || "Email inconnu";
                $("#userInfo").html(`Nom: ${nom} <br> Email: ${email}`);
            }).fail(function() {
                $("#userInfo").html("Utilisateur non trouvé.");
            });
        });
    });
</script>

<div class="container mt-4">
    <h1 class="text-center mb-4">Nos Recettes</h1>

    <br>
    <div id="message"></div>

    <div class="row" id="recettes-container">
    </div>
</div>

<?php include 'include/footer.inc.php'; ?>

</body>

</html>