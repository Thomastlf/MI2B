<?php session_start();
require('../php/getapikey.php');
$data = json_decode(file_get_contents("../json/menu.json"), true);
$panier = [];
$total = 0;
$remise_pack_valeur = 0; 

if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/php/connexion.php");
    exit();
}

$quantites_finales = [];

if (isset($_POST['qte'])) {
    foreach ($_POST['qte'] as $nom_plat => $quantite) {
        if ($quantite > 0) {
            $quantites_finales[$nom_plat] = $quantite;
        }
    }
}

// On gère le bouton "Pack Menu" s'il a été cliqué
if (isset($_POST['pack_menu']) && !empty($_POST['pack_menu'])) {
    $pays_pack = $_POST['pack_menu'];
    $prix_total_pack = 0;
    
    // On cherche 1 entrée, 1 plat et 1 dessert de ce pays
    $entree_ok = false; 
    $plat_ok = false; 
    $dessert_ok = false;

    foreach ($data as $plat) {
        if ($plat['pays'] == $pays_pack) {
            if ($plat['categorie'] == 'entree' && !$entree_ok) {
                $quantites_finales[$plat['nom']] = ($quantites_finales[$plat['nom']] ?? 0) + 1;
                $prix_total_pack += $plat['prix'];
                $entree_ok = true;
            } elseif ($plat['categorie'] == 'plat' && !$plat_ok) {
                $quantites_finales[$plat['nom']] = ($quantites_finales[$plat['nom']] ?? 0) + 1;
                $prix_total_pack += $plat['prix'];
                $plat_ok = true;
            } elseif ($plat['categorie'] == 'dessert' && !$dessert_ok) {
                $quantites_finales[$plat['nom']] = ($quantites_finales[$plat['nom']] ?? 0) + 1;
                $prix_total_pack += $plat['prix'];
                $dessert_ok = true;
            }
        }
    }
    $remise_pack_valeur = $prix_total_pack * 0.10;
}

// On construit le panier final avec toutes les infos
foreach ($quantites_finales as $nom_plat => $quantite) {
    foreach ($data as $plat) {
        if ($nom_plat == $plat['nom']) {
            $prix = $plat['prix'];
            $panier[] = [
                'nom' => $nom_plat,
                'quantite' => $quantite,
                'prix' => $prix,
                'sous_total' => $prix * $quantite
            ];
            $total = $total + ($prix * $quantite);
        }
    }
}

if ($panier == []) {
    header("Location: http://localhost:8000/php/menu.php"); 
    exit();
}

if (isset($_POST['timing']) && $_POST['timing'] == "plus_tard") {
    $_SESSION['date_heure'] = $_POST['date_heure'];
} else {
    $_SESSION['date_heure'] = date('Y-m-d H:i:s');
}
$_SESSION['panier'] = $panier;

// Gestion des remises de fidélité
$remise = 0;
$niveau = "Classique";
$utilisateurs = json_decode(file_get_contents("../json/utilisateur.json"), true);
foreach ($utilisateurs as $ligne) {
    if ($_SESSION['email'] == $ligne['email']) {
        $niveau = $ligne["niveau"] ?? "Classique";
        $remise = $ligne["remise"] ?? 0;
    }
}

$reduction_fidelite = $total * ($remise * 5) / 100;
$reduction_totale = $reduction_fidelite + $remise_pack_valeur;
$total = $total - $reduction_totale;

$css="";
$texteBouton="Passer en mode malvoyant";
if(isset($_COOKIE["theme"]) && $_COOKIE["theme"] == "true"){
    $css="../css/theme.css";
    $texteBouton="Passer en mode par défaut";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perso.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    
    <link id="css" rel="stylesheet" href=<?php echo $css; ?>><!-- js -->
    <script src="../js/theme.js" defer></script><!-- js / defer pour n'exécuter le script js qu'une fois que le navigateur aura chargé le html dans le dom -->
    <title>Panier - Tasty Country</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
            
            <button type="button" id="bouton" class="btn_theme"><?php echo $texteBouton; ?></button><!-- js -->
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Votre panier 🛒</legend>
                
                <?php foreach ($panier as $article) { ?>
                    <div class="info-row">
                        <div class="label"><?php echo $article['quantite']; ?>x <?php echo $article['nom']; ?></div>
                        <div class="value"><?php echo number_format($article['sous_total'], 2); ?> €</div>
                    </div>
                <?php } ?>

                <?php if ($remise_pack_valeur > 0): ?>
                    <div class="info-row" style="color: #e74c3c; font-weight: bold;">
                        <div class="label">🎁 Réduction Pack Destination (-10%) :</div>
                        <div class="value">- <?php echo number_format($remise_pack_valeur, 2); ?> €</div>
                    </div>
                <?php endif; ?>

                <div class="info-row" style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px;">
                    <div class="label" style="font-size: 1.2rem; font-weight: bold;">TOTAL :</div>
                    <div class="value" style="font-size: 1.2rem; font-weight: bold;">
                        <?php echo number_format($total, 2) . " €"; ?> 
                        <span style="font-size: 0.8em; color: gray;">(-<?php echo number_format($reduction_totale, 2); ?> € au total)</span>
                    </div>
                    <div class="value"><?php echo "Grade : " . $niveau; ?></div>
                    <div class="value"><?php echo "Niveau remise : " . $remise; ?></div>
                </div>

                <div class="info-row">
                    <div class="label">Pour :</div>
                    <div class="value"><?php if (isset($_POST['timing']) && $_POST['timing'] == "Maintenant"){
                        echo "Maintenant";
                    }else{
                        echo $_SESSION['date_heure'];
                    } ?></div>
                </div>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 20px;">
                    <?php
                    $trouve=True;
                    $fichier = "../json/commande.json";
                    if (file_exists($fichier)) {
                            $contenu = file_get_contents($fichier);
                            $commande = json_decode($contenu, true) ?? [];
                            }
                    while($trouve){
                        $transaction = uniqid();
                        $trouve=False;
                        foreach ($commande as $c) {
                            if ($c['id'] == $transaction) {
                                $trouve=True;
                            }                       
                        }
                    }
                    $montant = $total;
                    $vendeur = 'MI-2_B';
                    $retour = 'http://localhost:8000/php/retour_paiement.php';
                    $api_key = getAPIKey($vendeur); 
                    $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");?>
                    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                    <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
                    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                    <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
                    <input type='hidden' name='control' value='<?php echo $control; ?>'>
                    
                    <input type='submit' class="btn-edit" value="Valider et payer" style="width: 100%; font-size: 1.1rem;">
                </form>

                <form action="menu.php" style="margin-top: 10px;">
                    <button type="submit" class="btn-edit" style="width: 100%; background-color: #555;">Retour au menu</button>
                </form>
            </fieldset>
        </main>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tasty Country 🌍</h3>
                    <p>Le tour du monde dans votre assiette.</p>
                </div>

                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>📍 CyTech, Cergy</p>
                    <p>📞 01 23 45 67 89</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Projet Informatique</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
