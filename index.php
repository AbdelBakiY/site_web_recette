<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recettes | MonSiteDeRecettes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
</head>
<body>

<?php include 'include/header.inc.php'; ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Nos Recettes</h1>

    <br>
    <div id="message"></div>

    <div class="row" id="recettes-container">
        <!-- Les cartes de recettes seront insérées ici par script.js -->
    </div>
</div>

<?php include 'include/footer.inc.php'; ?>

</body>
</html>