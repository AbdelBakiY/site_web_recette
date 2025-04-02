<?php
$recettes = json_decode(file_get_contents('data/recettes.json'), true);
$recetteId = $_GET['id'] ?? null;

if ($recetteId !== null && isset($recettes[$recetteId])) {
    $recette = $recettes[$recetteId];
    $titre = $recette['nameFR'] ?? $recette['name'] ?? 'Titre inconnu';
    $image = $recette['imageURL'] ?? 'default.jpg';
    $steps = $recette['stepsFR'] ?? $recette['steps'] ?? [];
    $author = $recette['Author'] ?? 'Auteur inconnu';
    $without = $recette['Without'] ?? [];
} else {
    echo "Recette non trouvée.";
    exit;
}

include 'include/header.inc.php';
?>
<style>:root {
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
}</style>
<div class="container mt-4">
    <h1 class="text-center mb-4"><?php echo $titre; ?></h1>
    <div class="card mb-4">
        <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo $titre; ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo $titre; ?></h5>
            <p class="card-text"><?php echo $recette['name'] ?? 'Pas de description'; ?></p>
        </div>
        <div class="author mt-3">
            <small class="text-muted">Auteur : <?php echo $author; ?></small>
        </div>
        <div class="without mt-3">
            <small class="text-muted">Sans : <?php echo implode(", ", $without); ?></small>
        </div>
        <h5 class="mt-3">Étapes de préparation :</h5>
        <ul class="list-group list-group-flush">
            <?php foreach ($steps as $step) : ?>
                <li class="list-group-item"><?php echo $step; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include 'include/footer.inc.php'; ?>

</body>
</html>