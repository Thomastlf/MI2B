<?php
// On indique qu'on va renvoyer du format JSON
header('Content-Type: application/json');

$json_path = '../json/menu.json';

if (!file_exists($json_path)) {
    echo json_encode([]);
    exit;
}

$plats = json_decode(file_get_contents($json_path), true);

$pays = $_GET['pays'] ?? '';
$categorie = $_GET['categorie'] ?? '';
$allergene_a_eviter = $_GET['allergene'] ?? '';
$tri = $_GET['tri'] ?? '';

$resultat = [];

// Application des filtres
foreach ($plats as $p) {
    $match_pays = ($pays === '' || $p['pays'] === $pays);
    $match_cat = ($categorie === '' || $p['categorie'] === $categorie);
    
    // Filtre Sans Gluten / Sans Lactose / Sans Oeuf
    $match_allergene = true;
    if ($allergene_a_eviter !== '') {
        // On vérifie si l'allergène à éviter est dans la liste du plat (en ignorant les majuscules)
        $allergenes_plat = array_map('strtolower', $p['allergenes']);
        if (in_array(strtolower($allergene_a_eviter), $allergenes_plat)) {
            $match_allergene = false; // Le plat contient l'allergène, on le rejette
        }
    }

    // Si le plat correspond à tous les critères, on le garde
    if ($match_pays && $match_cat && $match_allergene) {
        $resultat[] = $p;
    }
}

// Application du tri (Croissant / Décroissant)
if ($tri === 'prix_asc') {
    usort($resultat, function($a, $b) { return $a['prix'] <=> $b['prix']; });
} elseif ($tri === 'prix_desc') {
    usort($resultat, function($a, $b) { return $b['prix'] <=> $a['prix']; });
}

// On renvoie le résultat au Javascript en format JSON 
echo json_encode($resultat);
?>
