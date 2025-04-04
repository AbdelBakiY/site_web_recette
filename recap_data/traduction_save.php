<?php
session_start();

// Debug - Afficher le contenu complet de la session et POST
error_log("SESSION: ".print_r($_SESSION, true));
error_log("POST: ".print_r($_POST, true));

// Vérification simplifiée et corrigée des permissions
if (!isset($_SESSION['roles']['attribue']) || 
   !(in_array('traducteur', $_SESSION['roles']['attribue']) || 
    in_array('chef', $_SESSION['roles']['attribue']))) {
    $_SESSION['error'] = "Accès refusé : permissions insuffisantes";
    error_log("Redirection: permissions insuffisantes");
    exit;
}

// Chemin ABSOLU vers le fichier JSON
$recettesFile = __DIR__.'/../data/recettes.json';
error_log("Chemin recettes.json: ".$recettesFile);

if (!file_exists($recettesFile)) {
    $_SESSION['error'] = "Fichier de recettes introuvable";
    error_log("Fichier introuvable");
    exit;
}

// Récupération de l'ID
$recetteId = $_POST['recette_id'] ?? null;
if (empty($recetteId)) {
    $_SESSION['error'] = "ID de recette manquant dans POST";
    error_log("ID recette manquant: ".print_r($_POST, true));
    exit;
}

// Chargement des recettes
$recettes = json_decode(file_get_contents($recettesFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    $_SESSION['error'] = "Erreur de lecture du fichier recettes";
    exit;
}

if (!isset($recettes[$recetteId])) {
    $_SESSION['error'] = "Recette ID $recetteId introuvable";
    exit;
}

// Récupération de la recette
$recette = $recettes[$recetteId];
$isChef = in_array('chef', $_SESSION['roles']['attribue'] ?? []);
$isTranslator = in_array('traducteur', $_SESSION['roles']['attribue'] ?? []);
$isOwner = ($_SESSION['email'] === ($recette['Author'] ?? '')) && $isChef;
// Fonction de validation améliorée
function canEditField($currentValue, $otherLangValue, $isChef, $isOwner, $isTranslator) {
    if ($isOwner || $isChef) return true;
    if ($isTranslator) return empty($currentValue) && !empty($otherLangValue);
    return false;
}

// Traitement des données
$errors = [];

// Traitement des noms
foreach (['name', 'nameFR'] as $field) {
    $otherField = ($field === 'name') ? 'nameFR' : 'name';
    if (isset($_POST[$field])) {
        if (canEditField($recette[$field] ?? '', $recette[$otherField] ?? '', $isChef, $isOwner, $isTranslator)) {
            $recette[$field] = trim($_POST[$field]);
        } else {
            $errors[] = "Permission refusée pour le champ $field";
        }
    }
}

// Traitement des ingrédients
foreach (['ingredients', 'ingredientsFR'] as $lang) {
    $otherLang = ($lang === 'ingredients') ? 'ingredientsFR' : 'ingredients';
    
    foreach ($_POST[$lang] ?? [] as $index => $ingredient) {
        if (isset($recette[$lang][$index])) {
            $currentName = $recette[$lang][$index]['name'] ?? '';
            $otherName = $recette[$otherLang][$index]['name'] ?? '';
            
            if (canEditField($currentName, $otherName, $isChef, $isOwner, $isTranslator)) {
                if (isset($ingredient['name'])) $recette[$lang][$index]['name'] = trim($ingredient['name']);
                if (isset($ingredient['quantity'])) $recette[$lang][$index]['quantity'] = trim($ingredient['quantity']);
            } else {
                $errors[] = "Permission refusée pour l'ingrédient #$index ($lang)";
            }
        }
    }
}

// Traitement des étapes
foreach (['steps', 'stepsFR'] as $lang) {
    $otherLang = ($lang === 'steps') ? 'stepsFR' : 'steps';
    
    foreach ($_POST[$lang] ?? [] as $index => $step) {
        if (isset($recette[$lang][$index])) {
            $currentStep = $recette[$lang][$index] ?? '';
            $otherStep = $recette[$otherLang][$index] ?? '';
            
            if (canEditField($currentStep, $otherStep, $isChef, $isOwner, $isTranslator)) {
                $recette[$lang][$index] = trim($step);
            } else {
                $errors[] = "Permission refusée pour l'étape #$index ($lang)";
            }
        }
    }
}

// Vérification finale
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: ../traduction.php?id=$recetteId");
    exit;
}

// Vérification de cohérence
$ingredientsCount = [count($recette['ingredients'] ?? []), count($recette['ingredientsFR'] ?? [])];
$stepsCount = [count($recette['steps'] ?? []), count($recette['stepsFR'] ?? [])];

if (max($ingredientsCount) !== min($ingredientsCount) || max($stepsCount) !== min($stepsCount)) {
    $_SESSION['error'] = "Le nombre d'éléments doit être identique dans les deux langues";
    header("Location: ../traduction.php?id=$recetteId");
    exit;
}


// Sauvegarde
try {
    $backup = $recettes;
    $recettes[$recetteId] = $recette;
    
    if (file_put_contents($recettesFile, json_encode($recettes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) === false) {
        throw new Exception("Erreur d'écriture du fichier");
    }
    
    $_SESSION['success'] = "Traduction enregistrée avec succès";
    header("Location: ../recette_details.php?id=$recetteId");
} catch (Exception $e) {
    // Restauration en cas d'erreur
    file_put_contents($recettesFile, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $_SESSION['error'] = "Erreur lors de l'enregistrement: " . $e->getMessage();
    header("Location: ../traduction.php?id=$recetteId");
}

exit;