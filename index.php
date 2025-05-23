<?php
$titre = "Recettes | MonSiteDeRecettes";
include 'include/header.inc.php'; ?>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "recap_data/getRecette.php",
            method: "GET",
            dataType: "json"
        }).done(function (data) {
            let container = $("#recettes-container");
            container.empty();

            if (data && data.length > 0) {
                data.forEach((recette, index) => {
                    let recetteId = index;
                    let image = recette.imageURL || "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80";
                    let titre = recette.nameFR || recette.name || "Titre inconnu";
                    let author = recette.Author || "Auteur inconnu";
                    let without = recette.Without || [];

                    // REMOVED localStorage like
                    const likes = recette.likes || 0;
                    const comments = recette.comments ? recette.comments.length : 0;

                    let card = `
        <div class="recette-card">
            <img src="${image}" alt="${titre}">
            <div class="recette-info">
                <h3><a href="recette_details.php?id=${recetteId}">${titre}</a></h3>
                <div class="meta-info">
                    <span class="meta-item"><i class="fas fa-user"></i> ${author}</span>
                </div>
                <div class="mb-2">
                    ${(without || []).map(item => `<span class="tag">Sans ${item}</span>`).join('')}
                </div>
                <div class="social-stats">
                    <span>
                        <i class="fas fa-heart"></i> ${likes}
                    </span>
                    <span>
                        <i class="fas fa-comment"></i> ${comments}
                    </span>
                </div>
                <a href="recette_details.php?id=${recetteId}" class="btn btn-primary mt-2">
                    <i class="fas fa-book-open"></i> Voir la recette
                </a>
            </div>
        </div>
    `;
                    container.append(card);
                });
            } else {
                container.append("<p class='text-center'>Aucune recette disponible pour le moment.</p>");
            }
        }).fail(function () {
            $("#recettes-container").html("<p class='text-danger text-center'>Erreur lors du chargement des recettes. Veuillez réessayer plus tard.</p>");
        });
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div id="recettes-container">
    <p class="text-center">Chargement des recettes...</p>
</div>

<?php include 'include/footer.inc.php'; ?>
</body>

</html>