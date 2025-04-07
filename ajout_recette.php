<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Données JSON invalides.']);
    exit;
}

$newRecette = [];

if (!empty($data['name'])) {
    $newRecette['name'] = $data['name'];
}
if (!empty($data['nameFR'])) {
    $newRecette['nameFR'] = $data['nameFR'];
}
if (empty($newRecette['name']) && !empty($newRecette['nameFR'])) {
    $newRecette['name'] = $newRecette['nameFR'];
}
if (empty($newRecette['name']) && empty($newRecette['nameFR'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Le nom (FR ou EN) est requis.']);
    exit;
}

$newRecette['Author'] = !empty($data['author']) ? $data['author'] : 'Unknown';

$image = trim($data['imageURL'] ?? '');
$newRecette['imageURL'] = filter_var($image, FILTER_VALIDATE_URL)
    ? $image
    : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';

$newRecette['Without'] = !empty($data['without']) ? (array)$data['without'] : [];

if (!empty($data['ingredients']) && is_array($data['ingredients'])) {
    $newRecette['ingredients'] = array_values($data['ingredients']);
}
if (!empty($data['ingredientsFR']) && is_array($data['ingredientsFR'])) {
    $newRecette['ingredientsFR'] = array_values($data['ingredientsFR']);
}

if (!empty($data['steps']) && is_array($data['steps'])) {
    $steps = array_filter(array_map('trim', $data['steps']), fn($s) => $s !== '');
    if (!empty($steps)) {
        $newRecette['steps'] = $steps;
        $newRecette['timers'] = array_fill(0, count($steps), 0);
    }
}
if (!empty($data['stepsFR']) && is_array($data['stepsFR'])) {
    $stepsFR = array_filter(array_map('trim', $data['stepsFR']), fn($s) => $s !== '');
    if (!empty($stepsFR)) {
        $newRecette['stepsFR'] = $stepsFR;
    }
}

$newRecette['originalURL'] = $data['originalURL'] ?? "";

$file = 'data/recettes.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
}

$recettes = json_decode(file_get_contents($file), true);
if (!is_array($recettes)) $recettes = [];

$recettes[] = $newRecette;
file_put_contents($file, json_encode($recettes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true]);
