<?php
session_start();
include 'include/header.inc.php';

// Vérification des permissions
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

// Initialisation des champs FR s'ils n'existent pas
if (!isset($recette['ingredientsFR'])) {
    $recette['ingredientsFR'] = array_map(function($ing) {
        return ['quantity' => $ing['quantity'], 'name' => '', 'type' => $ing['type']];
    }, $recette['ingredients']);
}

if (!isset($recette['stepsFR'])) {
    $recette['stepsFR'] = array_fill(0, count($recette['steps']), '');
}

if (!isset($recette['nameFR'])) {
    $recette['nameFR'] = '';
}
?>
<link rel="stylesheet" href="css/style.css">
<div class="text-end mb-3">
        <button class="btn btn-sm btn-outline-secondary" disabled title="Non disponible en mode traduction">
            <i class="fas fa-language"></i> 
            <?= $currentLang === 'fr' ? 'Switch to English' : 'Passer en Français' ?>
        </button>
    </div>
    <!-- Le reste de votre code existant... -->
</div>
    <h2>Traduction de la recette</h2>
    <form id="translation-form" action="recap_data/traduction_save.php" method="POST">
        <input type="hidden" name="recette_id" value="<?= $recetteId ?>">
        
        <div class="row">
            <!-- Colonne Français -->
            <div class="col-md-6 border-end">
                <h3>Français</h3>
                
                <!-- Nom -->
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <?php if (empty($recette['nameFR']) || $isChef): ?>
                        <input type="text" class="form-control <?= empty($recette['nameFR']) ? 'to-translate' : '' ?>" 
                               name="nameFR" value="<?= htmlspecialchars($recette['nameFR']) ?>"
                               placeholder="Traduire: <?= htmlspecialchars($recette['name']) ?>">
                    <?php else: ?>
                        <p class="form-control-static"><?= htmlspecialchars($recette['nameFR']) ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Ingrédients -->
                <div class="mb-3">
                    <label class="form-label">Ingrédients</label>
                    <?php foreach ($recette['ingredients'] as $index => $ingredient): 
                        $ingFR = $recette['ingredientsFR'][$index] ?? ['name' => '', 'quantity' => $ingredient['quantity']];
                        $canEdit = empty($ingFR['name']) || $isChef;
                    ?>
                        <div class="ingredient-group mb-2 <?= empty($ingFR['name']) ? 'to-translate' : '' ?>">
                            <?php if ($canEdit): ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" 
                                           name="ingredientsFR[<?= $index ?>][quantity]" 
                                           value="<?= htmlspecialchars($ingFR['quantity']) ?>">
                                    <input type="text" class="form-control" 
                                           name="ingredientsFR[<?= $index ?>][name]" 
                                           value="<?= htmlspecialchars($ingFR['name']) ?>"
                                           placeholder="Traduire: <?= htmlspecialchars($ingredient['name']) ?>">
                                    <span class="input-group-text"><?= $ingredient['type'] ?></span>
                                </div>
                            <?php else: ?>
                                <p class="form-control-static">
                                    <?= htmlspecialchars($ingFR['quantity']) ?> 
                                    <?= htmlspecialchars($ingFR['name']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Étapes -->
                <div class="mb-3">
                    <label class="form-label">Étapes de préparation</label>
                    <?php foreach ($recette['steps'] as $index => $step): 
                        $stepFR = $recette['stepsFR'][$index] ?? '';
                        $canEdit = empty($stepFR) || $isChef;
                    ?>
                        <div class="mb-2 <?= empty($stepFR) ? 'to-translate' : '' ?>">
                            <?php if ($canEdit): ?>
                                <textarea class="form-control" name="stepsFR[<?= $index ?>]" rows="2"
                                          placeholder="Traduire: <?= htmlspecialchars($step) ?>"><?= htmlspecialchars($stepFR) ?></textarea>
                            <?php else: ?>
                                <p class="form-control-static"><?= htmlspecialchars($stepFR) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Colonne Anglais (lecture seule) -->
            <div class="col-md-6">
                <h3>English (Reference)</h3>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <p class="form-control-static"><?= htmlspecialchars($recette['name']) ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Ingredients</label>
                    <?php foreach ($recette['ingredients'] as $ingredient): ?>
                        <p class="form-control-static">
                            <?= htmlspecialchars($ingredient['quantity']) ?> 
                            <?= htmlspecialchars($ingredient['name']) ?> 
                            (<?= $ingredient['type'] ?>)
                        </p>
                    <?php endforeach; ?>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Steps</label>
                    <?php foreach ($recette['steps'] as $step): ?>
                        <p class="form-control-static mb-2"><?= htmlspecialchars($step) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="recette_details.php?id=<?= $recetteId ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<style>
.to-translate {
    background-color: #fffde7;
    border-left: 3px solid #ffd600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation avant soumission
    document.getElementById('translation-form').addEventListener('submit', function(e) {
        let valid = true;
        
        // Vérifier les champs FR requis
        const frFields = document.querySelectorAll('[name^="nameFR"], [name^="ingredientsFR"], [name^="stepsFR"]');
        frFields.forEach(field => {
            if (!field.value.trim() && field.offsetParent !== null) {
                alert('Tous les champs en français doivent être remplis');
                valid = false;
            }
        });
        
        if (!valid) e.preventDefault();
    });
});
</script>

<?php include 'include/footer.inc.php'; ?>