<?php
session_start();

// Chargement des recettes
$recettes = json_decode(file_get_contents('data/recettes.json'), true);
$recetteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($recetteId === null || $recetteId === false || !isset($recettes[$recetteId])) {
    header("Location: index.php");
    exit;
}
$likes = 0;
$userLiked = false;
if (file_exists(filename: 'data/utilisateurs.json')) {
    $users = json_decode(file_get_contents('data/utilisateurs.json'), true);
    foreach ($users as $user) {
        if (isset($user['likes']) && in_array($recetteId, $user['likes'])) {
            $likes++;
            if (isset($_SESSION['email']) && $user['email'] === $_SESSION['email']) {
                $userLiked = true;
            }
        }
    }
}
$recette = $recettes[$recetteId];
$titre = htmlspecialchars($recette['nameFR'] ?? $recette['name'] ?? 'Titre inconnu');
$image = htmlspecialchars($recette['imageURL'] ?? 'https://via.placeholder.com/800x600?text=Recette');
$steps = $recette['stepsFR'] ?? $recette['steps'] ?? [];
$ingredients = $recette['ingredientsFR'] ?? $recette['ingredients'] ?? [];
$author = htmlspecialchars($recette['Author'] ?? 'Auteur inconnu');
$without = $recette['Without'] ?? [];


$commentsFile = 'data/comments.json';
$comments = [];

// Charger les commentaires
if (file_exists($commentsFile)) {
    $allComments = json_decode(file_get_contents($commentsFile), true);
    $comments = $allComments[$recetteId] ?? [];
}

include 'include/header.inc.php';
?>
<style>.btn-like {
    transition: all 0.3s ease;
}

.btn-like.liked {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

.btn-like:not(.liked) {
    background-color: transparent;
    border-color: #dc3545;
    color: #dc3545;
}</style>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= $image ?>" class="img-fluid rounded" alt="<?= $titre ?>">
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-like <?= $userLiked ? 'liked' : '' ?>" data-recette-id="<?= $recetteId ?>">
                    <i class="<?= $userLiked ? 'fas' : 'far' ?> fa-heart"></i>
                    <?= $userLiked ? 'Aimé' : 'J\'aime' ?>
                    <span class="like-count">(<?= $likes ?>)</span>
                </button>
                <span class="align-self-center">
                    <i class="fas fa-comment"></i> <span class="comment-count"><?= count($comments) ?></span>
                    commentaixres
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <h1><?= $titre ?></h1>
            <p class="text-muted">Par <?= $author ?></p>

            <div class="mb-3">
                <?php foreach ($without as $item): ?>
                    <span class="badge bg-secondary me-1">Sans <?= htmlspecialchars($item) ?></span>
                <?php endforeach; ?>
            </div>

            <h4>Ingrédients :</h4>
            <ul class="list-group mb-4">
                <?php foreach ($ingredients as $ing): ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($ing['quantity'] ?? '') ?>
                        <?= htmlspecialchars($ing['name'] ?? '') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
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
        </div>
    </div>
</div>
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
    $(document).ready(function() {
    // Gestion des likes
    $('.btn-like').click(function(e) {
        e.preventDefault();
        const btn = $(this);
        const recetteId = btn.data('recette-id');

        $.post('recap_data/like_recette.php', { 
            recette_id: recetteId 
        }, function(response) {
            if (response.success) {
                btn.toggleClass('liked');
                btn.find('i').toggleClass('fas far');
                
                // Mettre à jour le texte et le compteur
                btn.html(`
                    <i class="${response.liked ? 'fas' : 'far'} fa-heart"></i>
                    ${response.liked ? 'Aimé' : 'J\'aime'}
                    (<span class="like-count">${response.new_likes}</span>)
                `);
            }
        }, 'json');
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
    $(document).ready(function () {
        $('#comment-form').submit(function (e) {
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
                success: function (response) {
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
                error: function (xhr) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        alert(errorResponse.error || 'Erreur serveur');
                    } catch (e) {
                        alert('Erreur de communication avec le serveur');
                    }
                }
            });
        });
    });
</script>

<?php include 'include/footer.inc.php'; ?>