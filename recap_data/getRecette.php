<?php
header('Content-Type: application/json');

$recettes = json_decode(file_get_contents('../data/recettes.json'), true);

$likesParRecette = [];
$users = json_decode(file_get_contents('../data/utilisateurs.json'), true);
foreach ($users as $user) {
    if (isset($user['likes'])) {
        foreach ($user['likes'] as $recetteId) {
            if (!isset($likesParRecette[$recetteId])) {
                $likesParRecette[$recetteId] = 0;
            }
            $likesParRecette[$recetteId]++;
        }
    }
}

$comments = json_decode(file_get_contents('../data/comments.json'), true) ?: [];

$output = [];
foreach ($recettes as $id => $recette) {
    $output[] = [
        'id' => $id,
        'name' => $recette['name'] ?? '',
        'nameFR' => $recette['nameFR'] ?? '',
        'imageURL' => $recette['imageURL'] ?? '',
        'Author' => $recette['Author'] ?? '',
        'Without' => $recette['Without'] ?? [],
        'likes' => $likesParRecette[$id] ?? 0,
        'comments' => $comments[$id] ?? []
    ];
}

echo json_encode($output);