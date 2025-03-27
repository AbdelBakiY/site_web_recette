<?php
$titre = "Recettes | MonSiteDeRecettes";
include 'include/header.inc.php'; ?>
<style>
:root {
    --primary-color:rgb(63, 39, 242);
    --secondary-color: #a29bfe;
    --accent-color: #fd79a8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --text-color: #2d3436;
    --text-light: #636e72;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color:rgb(234, 235, 238);
    color: var(--text-color);
    line-height: 1.6;
}

#recettes-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.recette-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.recette-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.recette-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-bottom: 4px solid var(--primary-color);
}

.recette-card .recette-info {
    padding: 20px;
}

.recette-card h3 {
    margin: 0 0 10px 0;
    font-size: 1.5rem;
    color: var(--primary-color);
    font-weight: 600;
}

.recette-card h3 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.recette-card h3 a:hover {
    color: var(--accent-color);
}

.recette-card .meta-info {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.recette-card .meta-item {
    display: flex;
    align-items: center;
    color: var(--text-light);
    font-size: 0.9rem;
}

.recette-card .meta-item i {
    margin-right: 5px;
    color: var(--secondary-color);
}

.recette-card .tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 10px 0;
}

.recette-card .tag {
    background-color: var(--secondary-color);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.recette-card .voir-recette {
    display: inline-block;
    padding: 10px 20px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    margin-top: 10px;
    border: none;
    cursor: pointer;
}

.recette-card .voir-recette:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
}

.text-center {
    text-align: center;
}

.text-danger {
    color: #e74c3c;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.recette-card {
    animation: fadeIn 0.5s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
    #recettes-container {
        padding: 0 15px;
    }
    
    .recette-card .meta-info {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

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
                let detailLink = `recette_details.php?id=${recetteId}`;
                
                // Création des tags pour les restrictions alimentaires
                let tagsHtml = '';
                if (without.length > 0) {
                    without.forEach(item => {
                        tagsHtml += `<span class="tag">Sans ${item}</span>`;
                    });
                }

                let card = `
                    <div class="recette-card">
                        <img src="${image}" alt="${titre}">
                        <div class="recette-info">
                            <h3><a href="${detailLink}">${titre}</a></h3>
                            <div class="meta-info">
                                <span class="meta-item"><i class="fas fa-user"></i> ${author}</span>
                            </div>
                            <div class="tags">${tagsHtml}</div>
                            <a href="${detailLink}" class="voir-recette">
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