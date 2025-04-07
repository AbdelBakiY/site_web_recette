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

<div class="container mt-4">
<<<<<<< HEAD
    <h1 class="text-center mb-4"><?php echo $titre; ?></h1>
    <div class="card mb-4">
        <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo $titre; ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo $titre; ?></h5>
            <p class="card-text"><?php echo $recette['name'] ?? 'Pas de description'; ?></p>
=======
    <div class="row">
        <div class="col-md-6">
            <img src="<?= $image ?>" class="img-fluid rounded" alt="<?= $titre ?>">
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-outline-danger like-btn" data-recette-id="<?= $recetteId ?>">
                    <i class="fas fa-heart"></i>
                    <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                </button>
                <span class="align-self-center">
                    <i class="fas fa-comment"></i> <span class="comment-count"><?= count($comments) ?></span>
                    commentaixres
                </span>
            </div>
>>>>>>> 6a80a62ae6e1e1e6741487f79ffaabf72df91f9d
        </div>
        <div class="author mt-3">
            <small class="text-muted">Auteur : <?php echo $author; ?></small>
        </div>
<<<<<<< HEAD
        <div class="without mt-3">
            <small class="text-muted">Sans : <?php echo implode(", ", $without); ?></small>
=======
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h4>Préparation :</h4>
            <ol class="list-group list-group-numbered">
                <?php foreach ($steps as $step): ?>
                    <li class="list-group-item"><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h4>Commentaires :</h4>

            <?php if (empty($comments)): ?>
                <div class="alert alert-info">Aucun commentaire pour cette recette.</div>
            <?php else: ?>
                <div class="list-group mb-4">
                    <?php foreach ($comments as $comment): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($comment['author'] ?? 'Anonyme') ?></strong>
                                <small class="text-muted"><?= htmlspecialchars($comment['date'] ?? date('d/m/Y H:i')) ?></small>
                            </div>
                            <p class="mb-0 mt-2"><?= htmlspecialchars($comment['text'] ?? '') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['email'])): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter un commentaire</h5>
                        <form id="comment-form" method="POST">
                            <input type="hidden" name="recette_id" value="<?= $recetteId ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="comment_text" placeholder="Votre commentaire..."
                                    rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Poster
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <a href="login.php" class="alert-link">Connectez-vous</a> pour poster un commentaire.
                </div>
            <?php endif; ?>
>>>>>>> 6a80a62ae6e1e1e6741487f79ffaabf72df91f9d
        </div>
        <h5 class="mt-3">Étapes de préparation :</h5>
        <ul class="list-group list-group-flush">
            <?php foreach ($steps as $step) : ?>
                <li class="list-group-item"><?php echo $step; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<<<<<<< HEAD

<?php include 'include/footer.inc.php'; ?>

</body>
</html>
=======
<?php

if (
    isset($_SESSION['roles']['attribue']) &&
    (in_array('traducteur', $_SESSION['roles']['attribue']) ||
        in_array('chef', $_SESSION['roles']['attribue']))
):
    ?>
    <a href="traduction.php?id=<?= $recetteId ?>" class="btn btn-info mt-3 fixed-bottom"
        style="right: 20px; bottom: 20px; width: auto;">
        <i class="fas fa-language"></i> Traduire cette recette
    </a>
<?php endif; ?>
<script>
    $(document).ready(function () {
        // Gestion des likes avec localStorage
        $('.like-btn').click(function (e) {
            e.preventDefault();
            const btn = $(this);
            const recetteId = btn.data('recette-id');

            $.post('recap_data/like_recette.php', { recette_id: recetteId }, function (response) {
                if (response.success) {
                    // Mettre à jour l'interface
                    btn.toggleClass('active');
                    btn.html(`<i class="fas fa-heart"></i> ${response.liked ? 'Aimé' : 'J\'aime'} (<span class="like-count">${response.new_likes}</span>)`);

                    // Stocker l'état actuel dans localStorage
                    localStorage.setItem(`like_${recetteId}`, response.liked);
                    localStorage.setItem(`like_count_${recetteId}`, response.new_likes);
                }
            });
        });

        // Vérifier localStorage au chargement
        const recetteId = <?= $recetteId ?>;
        const storedLike = localStorage.getItem(`like_${recetteId}`);
        const storedCount = localStorage.getItem(`like_count_${recetteId}`);

        if (storedLike !== null) {
            const btn = $('.like-btn');
            btn.toggleClass('active', storedLike === 'true');
            btn.find('.like-count').text(storedCount);
            btn.find('i').next().text(storedLike === 'true' ? 'Aimé' : 'J\'aime');
        }
    });

    // Corrigez la requête AJAX pour les commentaires
    $(document).ready(function() {
    $('#comment-form').submit(function(e) {
        e.preventDefault();
        
        // Validation côté client
        const commentText = $(this).find('textarea').val().trim();
        if (!commentText) {
            alert('Le commentaire ne peut pas être vide');
            return;
        }

        $.ajax({
            url: 'recap_data/add_comment.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const dateObj = new Date(response.comment.date);
                    const formattedDate = dateObj.toLocaleDateString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const newComment = `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>${response.comment.author}</strong>
                            <small class="text-muted">${formattedDate}</small>
                        </div>
                        <p class="mb-0 mt-2">${response.comment.text}</p>
                    </div>`;

                    if ($('.list-group').length) {
                        $('.list-group').prepend(newComment);
                    } else {
                        $('.alert-info').replaceWith(`<div class="list-group">${newComment}</div>`);
                    }

                    $('#comment-form textarea').val('');
                    $('.comment-count').text(response.total_comments);
                    $('.alert-info').hide();
                } else {
                    alert(response.error || 'Erreur lors de l\'ajout du commentaire');
                }
            },
            error: function(xhr) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    alert(errorResponse.error || 'Erreur serveur');
                } catch(e) {
                    alert('Erreur de communication avec le serveur');
                }
            }
        });
    });
});
</script>

<?php include 'include/footer.inc.php'; ?>
>>>>>>> 6a80a62ae6e1e1e6741487f79ffaabf72df91f9d
