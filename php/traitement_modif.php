<?php
session_start();
require('../php/getapikey.php');

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$id_commande = $_POST['id_commande'];
$montant_supplementaire = floatval($_POST['montant_supplementaire']);
$nouvelles_quantites = $_POST['qte'] ?? [];

$fichier = "../json/commande.json";
$commandes = json_decode(file_get_contents($fichier), true);
$menu = json_decode(file_get_contents("../json/menu.json"), true);

$nouveaux_articles = [];
$nouveau_total = 0;

foreach ($nouvelles_quantites as $nom_plat => $qte) {
    if ($qte > 0) {
        foreach ($menu as $plat) {
            if ($plat['nom'] == $nom_plat) {
                $nouveaux_articles[] = [
                    'nom' => $nom_plat,
                    'quantite' => intval($qte),
                    'prix' => $plat['prix'],
                    'sous_total' => $plat['prix'] * $qte
                ];
                $nouveau_total += ($plat['prix'] * $qte);
            }
        }
    }
}

$index_a_modifier = -1;
foreach ($commandes as $index => $cmd) {
    if ($cmd['id'] == $id_commande && $cmd['client'] == $_SESSION['email']) {
        $index_a_modifier = $index;
        break;
    }
}

if ($index_a_modifier !== -1) {
    if ($montant_supplementaire <= 0) {
        $commandes[$index_a_modifier]['articles'] = $nouveaux_articles;
        file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));
        
        header("Location: profil.php");
        exit();
    } else {
        $_SESSION['modif_en_attente'] = [
            'id_commande' => $id_commande,
            'nouveaux_articles' => $nouveaux_articles,
            'nouveau_total' => $nouveau_total
        ];

        $transaction = uniqid(); 
        $vendeur = 'MI-2_B';
        $retour = 'http://localhost:8000/php/retour_paiement_modif.php';
        $api_key = getAPIKey($vendeur); 
        $control = md5($api_key . "#" . $transaction . "#" . $montant_supplementaire . "#" . $vendeur . "#" . $retour . "#");
        
        echo "<form id='form_banque' action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
                <input type='hidden' name='transaction' value='$transaction'>
                <input type='hidden' name='montant' value='$montant_supplementaire'>
                <input type='hidden' name='vendeur' value='$vendeur'>
                <input type='hidden' name='retour' value='$retour'>
                <input type='hidden' name='control' value='$control'>
              </form>
              <script>document.getElementById('form_banque').submit();</script>";
        exit();
    }
}
?>
