<?php
session_start();
include 'include/header.inc.php';


// Vérification des permissions adaptée à votre structure JSON


if (!isset($_SESSION['roles']['attribue']) || 
    !(in_array('traducteur', $_SESSION['roles']['attribue']) || 
      in_array('chef', $_SESSION['roles']['attribue']))) {
    header("Location: index.php");
    exit;
}

// Chargement de la recette
$recetteId = $_GET['id'] ?? null;
$recettes = json_decode(file_get_contents('data/recettes.json'), true);

if (!isset($recettes[$recetteId])) {
    header("Location: index.php");
    exit;
}

$recette = $recettes[$recetteId];
$isOwner = ($_SESSION['email'] === ($recette['Author'] ?? ''));
$isTranslator = in_array('traducteur', $_SESSION['roles']['attribue'] ?? []);
$isChef = in_array('chef', $_SESSION['roles']['attribue'] ?? []);
?>

<div class="container mt-4">
    <h2>Traduction de la recette</h2>
    <form id="translation-form" action="recap_data/traduction_save.php" method="POST" onsubmit="return validateForm()">
    <input type="hidden" name="recette_id" value="<?= $recetteId ?>">
        <div class="row">
            <!-- Colonne Français -->
            <div class="col-md-6">
                <h3>Français</h3>
                
                <!-- Nom -->
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <?php if (empty($recette['nameFR']) || $isChef): ?>
                        <input type="text" class="form-control" name="nameFR" 
                               value="<?= htmlspecialchars($recette['nameFR'] ?? $recette['name'] ?? '') ?>">
                    <?php else: ?>
                        <p class="form-control-static"><?= htmlspecialchars($recette['nameFR'] ?? $recette['name'] ?? '') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Ingrédients -->
                <div class="mb-3">
                    <label class="form-label">Ingrédients</label>
                    <?php foreach (($recette['ingredients'] ?? []) as $index => $ingredient): ?>
                        <div class="ingredient-group mb-2">
                            <?php if (empty($ingredient['nameFR']) || $isChef): ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           name="ingredientsFR[<?= $index ?>][quantity]" 
                                           value="<?= htmlspecialchars($ingredient['quantity'] ?? '') ?>" 
                                           placeholder="Quantité">
                                    <input type="text" class="form-control" 
                                           name="ingredientsFR[<?= $index ?>][name]" 
                                           value="<?= htmlspecialchars($ingredient['nameFR'] ?? $ingredient['name'] ?? '') ?>" 
                                           placeholder="Nom ingrédient">
                                </div>
                            <?php else: ?>
                                <p class="form-control-static">
                                    <?= htmlspecialchars($ingredient['quantity'] ?? '') ?> 
                                    <?= htmlspecialchars($ingredient['nameFR'] ?? $ingredient['name'] ?? '') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Étapes -->
                <div class="mb-3">
                    <label class="form-label">Étapes de préparation</label>
                    <?php foreach (($recette['steps'] ?? []) as $index => $step): ?>
                        <div class="mb-2">
                            <?php if (empty($recette['stepsFR'][$index]) || $isChef): ?>
                                <textarea class="form-control" name="stepsFR[<?= $index ?>]" rows="2">
                                    <?= htmlspecialchars($recette['stepsFR'][$index] ?? $step ?? '') ?>
                                </textarea>
                            <?php else: ?>
                                <p class="form-control-static">
                                    <?= htmlspecialchars($recette['stepsFR'][$index] ?? $step ?? '') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Colonne Anglais -->
            <div class="col-md-6">
                <h3>English</h3>
                
                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <?php if (empty($recette['name']) || $isChef): ?>
                        <input type="text" class="form-control" name="name" 
                               value="<?= htmlspecialchars($recette['name'] ?? '') ?>">
                    <?php else: ?>
                        <p class="form-control-static"><?= htmlspecialchars($recette['name'] ?? '') ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Ingredients -->
                <div class="mb-3">
                    <label class="form-label">Ingredients</label>
                    <?php foreach (($recette['ingredients'] ?? []) as $index => $ingredient): ?>
                        <div class="ingredient-group mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                       name="ingredients[<?= $index ?>][quantity]" 
                                       value="<?= htmlspecialchars($ingredient['quantity'] ?? '') ?>" 
                                       placeholder="Quantity" readonly>
                                <input type="text" class="form-control" 
                                       name="ingredients[<?= $index ?>][name]" 
                                       value="<?= htmlspecialchars($ingredient['name'] ?? '') ?>" 
                                       placeholder="Ingredient name" readonly>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Steps -->
                <div class="mb-3">
                    <label class="form-label">Preparation Steps</label>
                    <?php foreach (($recette['steps'] ?? []) as $index => $step): ?>
                        <div class="mb-2">
                            <textarea class="form-control" rows="2" readonly>
                                <?= htmlspecialchars($step ?? '') ?>
                            </textarea>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="recette_details.php?id=<?= $recetteId ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer les traductions
            </button>
        </div>
    </form>
</div>

<script>
    function validateForm() {
    console.log("Données envoyées :", $("#translation-form").serialize());
    return true; // Permet l'envoi du formulaire
}
// Script pour désactiver les champs non modifiables
$(document).ready(function() {
    $('input[readonly], textarea[readonly]').css('background-color', '#f8f9fa');
});
</script>

<?php include 'include/footer.inc.php'; ?>