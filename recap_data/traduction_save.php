<?php
session_start();

// Vérification des permissions
if (!isset($_SESSION['roles']['attribue']) || 
    !(in_array('traducteur', $_SESSION['roles']['attribue']) || 
     in_array('chef', $_SESSION['roles']['attribue']))) {
    $_SESSION['error'] = "Accès refusé : permissions insuffisantes";
    header("Location: ../index.php");
    exit;
}

// Chemin absolu sécurisé
$recettesFile = __DIR__.'/../data/recettes.json';
if (!file_exists($recettesFile)) {
    $_SESSION['error'] = "Fichier de recettes introuvable";
    header("Location: ../index.php");
    exit;
}

// Récupération de l'ID
$recetteId = $_POST['recette_id'] ?? null;
if (empty($recetteId)) {
    $_SESSION['error'] = "ID de recette manquant";
    header("Location: ../index.php");
    exit;
}

// Chargement des recettes
$recettes = json_decode(file_get_contents($recettesFile), true);
if (!isset($recettes[$recetteId])) {
    $_SESSION['error'] = "Recette introuvable";
    header("Location: ../index.php");
    exit;
}

$recette = $recettes[$recetteId];
$isOwner = ($_SESSION['email'] === ($recette['Author'] ?? ''));
$isTranslator = in_array('traducteur', $_SESSION['roles']['attribue'] ?? []);
$isChef = in_array('chef', $_SESSION['roles']['attribue'] ?? []);

// Fonction de vérification des permissions
function canEdit($currentValue, $isChef, $isTranslator) {
    if ($isChef) return true;
    if ($isTranslator && empty(trim($currentValue ?? ''))) return true;
    return false;
}

// Initialisation des champs FR s'ils n'existent pas
if (!isset($recette['ingredientsFR'])) {
    $recette['ingredientsFR'] = array_map(function($ing) {
        return ['quantity' => $ing['quantity'], 'name' => '', 'type' => $ing['type']];
    }, $recette['ingredients']);
}

if (!isset($recette['stepsFR'])) {
    $recette['stepsFR'] = array_fill(0, count($recette['steps']), '');
}

// Traitement des données
$errors = [];

// Traitement du nom
if (isset($_POST['nameFR'])) {
    if (canEdit($recette['nameFR'], $isChef, $isTranslator)) {
        $recette['nameFR'] = trim($_POST['nameFR']);
    } elseif (!empty($recette['nameFR'])) {
        $errors[] = "Vous n'avez pas la permission de modifier le nom français";
    }
}

// Traitement des ingrédients
foreach ($_POST['ingredientsFR'] ?? [] as $index => $ingredient) {
    if (isset($recette['ingredientsFR'][$index])) {
        $currentName = $recette['ingredientsFR'][$index]['name'] ?? '';
        
        if (canEdit($currentName, $isChef, $isTranslator)) {
            $recette['ingredientsFR'][$index]['name'] = trim($ingredient['name']);
            $recette['ingredientsFR'][$index]['quantity'] = trim($ingredient['quantity']);
        } elseif (!empty($currentName)) {
            $errors[] = "Permission refusée pour l'ingrédient #" . ($index + 1);
        }
    }
}

// Traitement des étapes
foreach ($_POST['stepsFR'] ?? [] as $index => $step) {
    if (isset($recette['stepsFR'][$index])) {
        $currentStep = $recette['stepsFR'][$index] ?? '';
        
        if (canEdit($currentStep, $isChef, $isTranslator)) {
            $recette['stepsFR'][$index] = trim($step);
        } elseif (!empty($currentStep)) {
            $errors[] = "Permission refusée pour l'étape #" . ($index + 1);
        }
    }
}

// Vérification de cohérence
if (count($recette['ingredients']) !== count($recette['ingredientsFR']) || 
    count($recette['steps']) !== count($recette['stepsFR'])) {
    $errors[] = "Le nombre d'éléments doit être identique dans les deux langues";
}

// Gestion des erreurs
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: ../traduction.php?id=$recetteId");
    exit;
}

// Sauvegarde sécurisée
try {
    $recettes[$recetteId] = $recette;
    $backup = $recettes;
    
    if (file_put_contents(
        $recettesFile,
        json_encode($recettes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    ) === false) {
        throw new Exception("Erreur d'écriture du fichier");
    }
    
    $_SESSION['success'] = "Traduction enregistrée avec succès";
    header("Location: ../recette_details.php?id=$recetteId");
} catch (Exception $e) {
    file_put_contents($recettesFile, json_encode($backup, JSON_PRETTY_PRINT));
    $_SESSION['error'] = "Erreur lors de l'enregistrement: " . $e->getMessage();
    header("Location: ../traduction.php?id=$recetteId");
}