<?php
$titre = "Recettes | MonSiteDeRecettes";
include 'include/header.inc.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Découvrez nos délicieuses recettes</h1>
        <p class="lead text-muted">Des plats savoureux pour toutes les occasions</p>
    </div>

    <div id="recettes-container" class="row g-4">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des recettes...</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $.ajax({
        url: "recap_data/getRecette.php",
        method: "GET",
        dataType: "json"
    }).done(function(data) {
        let container = $("#recettes-container");
        container.empty();

        if (data && data.length > 0) {
            data.forEach((recette, index) => {
                let recetteId = index;
                let image = recette.imageURL || "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80";
                let titre = recette.nameFR || recette.name || "Titre inconnu";
                let author = recette.Author || "Auteur inconnu";
                let without = recette.Without || [];
                const likes = recette.likes || 0;
                const comments = recette.comments ? recette.comments.length : 0;

                let card = `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <img src="${image}" class="card-img-top" alt="${titre}" style="height: 200px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${titre}</h5>
                            <p class="text-muted mb-2"><small><i class="fas fa-user"></i> ${author}</small></p>
                            
                            <div class="mb-3">
                                ${without.map(item => `<span class="badge bg-light text-dark border me-1">Sans ${item}</span>`).join('')}
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div>
                                    <span class="me-3"><i class="fas fa-heart text-danger"></i> ${likes}</span>
                                    <span><i class="fas fa-comment text-primary"></i> ${comments}</span>
                                </div>
                                <a href="recette_details.php?id=${recetteId}" class="btn btn-sm btn-outline-primary">
                                    Voir <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`;
                container.append(card);
            });
        } else {
            container.html(`<div class="col-12">
                <div class="alert alert-info text-center">Aucune recette disponible pour le moment.</div>
            </div>`);
        }
    }).fail(function() {
        $("#recettes-container").html(`<div class="col-12">
            <div class="alert alert-danger text-center">Erreur lors du chargement des recettes. Veuillez réessayer plus tard.</div>
        </div>`);
    });
});
</script>

<?php include 'include/footer.inc.php'; ?>