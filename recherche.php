<?php
$titre = "Résultats de recherche | MonSiteDeRecettes";
include 'include/header.inc.php';

// Récupérer le terme de recherche
$searchTerm = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

?>

<style>
/* (Reprenez le même CSS que dans votre fichier recettes.php) */
:root {
    --primary-color: rgb(63, 39, 242);
    --secondary-color: #a29bfe;
    --accent-color: #fd79a8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --text-color: #2d3436;
    --text-light: #636e72;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: rgb(234, 235, 238);
    color: var(--text-color);
    line-height: 1.6;
}

.search-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 20px;
}

.search-header {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

/* (Reprenez le reste de votre CSS pour les cartes de recettes) */
</style>

<div class="search-container">
    <div class="search-header">
        <h2>Résultats de recherche pour "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
        
        <div class="filters mt-3">
            <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">Toutes</button>
            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="Vegan">Vegan</button>
            <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="NoGluten">Sans gluten</button>
            <button class="btn btn-sm btn-outline-info filter-btn" data-filter="NoMilk">Sans lait</button>
        </div>
    </div>

    <div id="search-results" class="row">
        <p class="text-center">Chargement des résultats...</p>
    </div>
</div>

<script>
$(document).ready(function() {
    const searchTerm = "<?php echo addslashes($searchTerm); ?>";
    let allRecipes = [];
    
    // Charger les recettes
    $.getJSON("recap_data/getRecette.php", function(data) {
        allRecipes = data;
        filterAndDisplayRecipes();
    }).fail(function() {
        $("#search-results").html('<p class="text-danger text-center">Erreur lors du chargement des recettes</p>');
    });

    // Filtrer et afficher les recettes
    function filterAndDisplayRecipes() {
        let filteredRecipes = allRecipes.filter(recipe => {
            // Si recherche vide, on montre tout
            if (!searchTerm) return true;
            
            // Recherche dans le nom
            if ((recipe.nameFR && recipe.nameFR.toLowerCase().includes(searchTerm)) || 
                (recipe.name && recipe.name.toLowerCase().includes(searchTerm))) {
                return true;
            }
            
            // Recherche dans les ingrédients
            const ingredients = recipe.ingredientsFR || recipe.ingredients || [];
            for (const ing of ingredients) {
                if (ing.name && ing.name.toLowerCase().includes(searchTerm)) {
                    return true;
                }
            }
            
            // Recherche dans les étapes
            const steps = recipe.stepsFR || recipe.steps || [];
            for (const step of steps) {
                if (step && step.toLowerCase().includes(searchTerm)) {
                    return true;
                }
            }
            
            return false;
        });
        
        displayRecipes(filteredRecipes);
    }

    // Afficher les recettes
    function displayRecipes(recipes) {
        const container = $("#search-results");
        container.empty();

        if (recipes.length === 0) {
            container.html('<p class="text-center">Aucune recette ne correspond à votre recherche.</p>');
            return;
        }

        recipes.forEach(function(recipe, index) {
            const recetteId = index;
            const image = recipe.imageURL || "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80";
            const titre = recipe.nameFR || recipe.name || "Titre inconnu";
            const author = recipe.Author || "Auteur inconnu";
            const without = recipe.Without || [];
            const detailLink = `recette_details.php?id=${recetteId}`;

            // Créer les tags
            let tagsHtml = '';
            without.forEach(item => {
                let displayText = item;
                if (item === "NoGluten") displayText = "Sans gluten";
                if (item === "NoMilk") displayText = "Sans lait";
                if (item === "Vegan") displayText = "Vegan";
                
                tagsHtml += `<span class="badge bg-secondary me-1">${displayText}</span>`;
            });

            // Calcul du temps total
            const totalTime = recipe.timers ? recipe.timers.reduce((a, b) => a + b, 0) : 0;
            const hours = Math.floor(totalTime / 60);
            const minutes = totalTime % 60;
            const timeString = hours > 0 ? `${hours}h${minutes.toString().padStart(2, '0')}` : `${minutes}min`;

            const card = `
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="${image}" class="card-img-top" alt="${titre}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${titre}</h5>
                            <p class="card-text"><small class="text-muted">Par ${author}</small></p>
                            <div class="mb-2">${tagsHtml}</div>
                            <p><i class="fas fa-clock"></i> Temps total: ${timeString}</p>
                            <a href="${detailLink}" class="btn btn-primary">Voir la recette</a>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    // Gestion des filtres supplémentaires
    $(".filter-btn").click(function() {
        $(".filter-btn").removeClass("active");
        $(this).addClass("active");
        filterAndDisplayRecipes();
    });

    // Fonction étendue avec filtres
    function filterAndDisplayRecipes() {
        const activeFilter = $(".filter-btn.active").data("filter");
        
        let filteredRecipes = allRecipes.filter(recipe => {
            // Filtre par terme de recherche
            if (searchTerm) {
                const foundInName = (recipe.nameFR && recipe.nameFR.toLowerCase().includes(searchTerm)) || 
                                  (recipe.name && recipe.name.toLowerCase().includes(searchTerm));
                
                const foundInIngredients = (recipe.ingredientsFR || recipe.ingredients || []).some(ing => 
                    ing.name && ing.name.toLowerCase().includes(searchTerm));
                
                const foundInSteps = (recipe.stepsFR || recipe.steps || []).some(step => 
                    step && step.toLowerCase().includes(searchTerm));
                
                if (!foundInName && !foundInIngredients && !foundInSteps) {
                    return false;
                }
            }
            
            // Filtre par type (vegan, sans gluten, etc.)
            if (activeFilter !== "all" && !recipe.Without?.includes(activeFilter)) {
                return false;
            }
            
            return true;
        });
        
        displayRecipes(filteredRecipes);
    }
});
</script>

<?php include 'include/footer.inc.php'; ?>