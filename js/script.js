$(document).ready(function () {
    // Charger les recettes
    $.ajax({
        url: "recap_data/getRecette.php",
        method: "GET",
        dataType: "json"
    }).done(function (data) {
        let container = $("#recettes-container");
        container.empty();

        data.forEach(recette => {
            // Vérification des noms de clés pour s'assurer qu'on utilise les bons noms du JSON
            let image = recette.imageURL || "default.jpg"; // Utilisation de imageURL du JSON
            let titre = recette.nameFR || recette.name || "Titre inconnu"; // Utilisation de nameFR ou name
            let description = recette.name || "Pas de description"; // Utilisation du name pour une brève description
            let steps = recette.stepsFR || recette.steps || []; // Utilisation de stepsFR pour les étapes en français
            let author = recette.author ; // Utilisation de author pour l'auteur
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
    }).fail(function () {
        console.log("Erreur lors du chargement des recettes.");
    });

    // Rechercher un utilisateur
    $("#searchUser").on("click", function () {
        let prenom = $("#prenomInput").val();
        $.ajax({
            url: "get_utilisateur.php",
            method: "GET",
            data: { prenom: prenom },
            dataType: "json"
        }).done(function (user) {
            // Vérification des noms de clés pour l'utilisateur
            let nom = user.nom || user.lastname || "Nom inconnu"; // Adapter selon JSON
            let email = user.email || user.mail || "Email inconnu"; // Adapter selon JSON

            $("#userInfo").html(`Nom: ${nom} <br> Email: ${email}`);
        }).fail(function () {
            $("#userInfo").html("Utilisateur non trouvé.");
        });
    });
});