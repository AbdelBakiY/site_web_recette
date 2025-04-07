<?php
$titre = "Recettes | MonSiteDeRecettes";
include 'include/header.inc.php'; ?>

<script>
    $(document).ready(function() {
        // Chargement des recettes existantes
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
                                    <span><i class="fas fa-heart"></i> ${likes}</span>
                                    <span><i class="fas fa-comment"></i> ${comments}</span>
                                </div>
                                <a href="recette_details.php?id=${recetteId}" class="btn btn-primary mt-2">
                                    <i class="fas fa-book-open"></i> Voir la recette
                                </a>
                            </div>
                        </div>`;
                    container.append(card);
                });
            } else {
                container.append("<p class='text-center'>Aucune recette disponible pour le moment.</p>");
            }
        }).fail(function() {
            $("#recettes-container").html("<p class='text-danger text-center'>Erreur lors du chargement des recettes. Veuillez réessayer plus tard.</p>");
        });

        // Formulaire ajout recette
        $("#toggle-ajout-form").on("click", function() {
            $("#ajout-form-container").slideToggle();
        });

        const langueCheckboxes = $("input[name='langue']");
        const formWrapper = $("#form-fields-wrapper");

        langueCheckboxes.on("change", function() {
            formWrapper.empty();
            const checked = langueCheckboxes.filter(":checked").map((_, el) => $(el).val()).get();

            if (checked.includes("FR")) {
                formWrapper.append(`
                    <div class="form-section">
                        <label>Titre (FR)</label>
                        <input type="text" class="form-input" name="nameFR" required>
                    </div>
                    <div class="form-section" id="ingredientsFR-block">
                        <label>Ingrédients (FR)</label>
                        <div class="ingredients-list" id="ingredientsFR-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addIngredient('FR')">+ Ajouter un ingrédient</button>
                    </div>
                    <div class="form-section">
                        <label>Étapes (FR)</label>
                        <textarea class="form-input" name="stepsFR" rows="3" placeholder="Une étape par ligne"></textarea>
                    </div>`);
            }

            if (checked.includes("EN")) {
                formWrapper.append(`
                    <div class="form-section">
                        <label>Title (EN)</label>
                        <input type="text" class="form-input" name="name" required>
                    </div>
                    <div class="form-section" id="ingredientsEN-block">
                        <label>Ingredients (EN)</label>
                        <div class="ingredients-list" id="ingredientsEN-list"></div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addIngredient('EN')">+ Add Ingredient</button>
                    </div>
                    <div class="form-section">
                        <label>Steps (EN)</label>
                        <textarea class="form-input" name="steps" rows="3" placeholder="One step per line"></textarea>
                    </div>`);
            }
        });

        // Soumission du formulaire
        $("#ajout-recette-form").on("submit", function(e) {
            e.preventDefault();

            const rawData = $(this).serializeArray();
            const data = {};

            rawData.forEach(field => {
                const name = field.name.replace(/\[|\]/g, '.').replace(/\.+$/, '');
                const keys = name.split('.');
                let ref = data;
                keys.forEach((key, i) => {
                    if (i === keys.length - 1) {
                        if (!ref[key]) ref[key] = field.value;
                        else if (Array.isArray(ref[key])) ref[key].push(field.value);
                        else ref[key] = [ref[key], field.value];
                    } else {
                        if (!ref[key]) ref[key] = {};
                        ref = ref[key];
                    }
                });
            });

            ["steps", "stepsFR"].forEach(field => {
                if (data[field]) {
                    data[field] = data[field].split('\n').filter(line => line.trim() !== "");
                }
            });

            $.ajax({
                url: "ajout_recette.php",
                method: "POST",
                data: JSON.stringify(data),
                contentType: "application/json"
            }).done(function() {
                $("#form-message").text("Recette ajoutée avec succès").removeClass("error").addClass("success");
                $("#ajout-recette-form")[0].reset();
                setTimeout(() => location.reload(), 1000);
            }).fail(function() {
                $("#form-message").text("Erreur à l'ajout").removeClass("success").addClass("error");
            });
        });
    });

    function addIngredient(lang) {
        const container = document.getElementById(`ingredients${lang}-list`);
        const index = container.children.length;
        const html = `
            <div class="ingredient-row mb-2">
                <input type="text" class="form-input" placeholder="Quantité" name="ingredients${lang}[${index}][quantity]" style="width: 20%;">
                <input type="text" class="form-input" placeholder="Nom" name="ingredients${lang}[${index}][name]" style="width: 50%;">
                <input type="text" class="form-input" placeholder="Type" name="ingredients${lang}[${index}][type]" style="width: 25%;">
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="ajout-recette text-center" style="margin-top: 2%;">
    <button id="toggle-ajout-form" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> Ajouter une recette
    </button>

    <div id="ajout-form-container" style="display: none;">
        <div class="form-container-ajout">
            <h2>Nouvelle recette</h2>
            <div class="form-section">
                <label>Langue :</label>
                <div class="langue-options">
                    <label><input type="checkbox" name="langue" value="FR"> Français</label>
                    <label><input type="checkbox" name="langue" value="EN"> Anglais</label>
                </div>
            </div>

            <form id="ajout-recette-form">
                <div id="form-fields-wrapper"></div>
                <div class="form-section">
                    <label for="imageURL">Image (URL)</label>
                    <input type="text" class="form-input" name="imageURL" placeholder="https://...">
                </div>
                <div class="form-section">
                    <label for="author">Auteur</label>
                    <input type="text" class="form-input" name="author" placeholder="Unknown si vide">
                </div>
                <div class="form-section">
                    <label>Sans :</label>
                    <div class="without-options">
                        <label><input type="checkbox" name="without[]" value="NoMilk"> NoMilk</label>
                        <label><input type="checkbox" name="without[]" value="NoGluten"> NoGluten</label>
                        <label><input type="checkbox" name="without[]" value="Vegan"> Vegan</label>
                        <label><input type="checkbox" name="without[]" value="Vegetarian"> Vegetarian</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Envoyer la recette</button>
                <p id="form-message" class="text-center mt-2"></p>
            </form>
        </div>
    </div>
</div>

<div id="recettes-container">
    <p class="text-center">Chargement des recettes...</p>
</div>

<?php include 'include/footer.inc.php'; ?>
</body>
</html>
