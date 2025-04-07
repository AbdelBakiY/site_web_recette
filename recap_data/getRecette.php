<?php
header('Content-Type: application/json');
$recettes = file_get_contents('../data/recettes.json');
echo $recettes;
?>