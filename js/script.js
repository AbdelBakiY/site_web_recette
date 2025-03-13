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
            // Verification des noms de clés pour s'assurer qu'on utilise les bons noms du JSON
            let image = recette.imageURL || "default.jpg"; 
            let titre = recette.nameFR || recette.name || "Titre inconnu"; 
            let description = recette.name || "Pas de description"; 
            let steps = recette.stepsFR || recette.steps || []; 
            let author = recette.author ; 
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

});