<?php
session_start();
require_once 'getapikey.php';

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$json_path = "../json/menu.json";
$catalogue = [];
if (file_exists($json_path)) {
    $catalogue = json_decode(file_get_contents($json_path), true);
}

$panier_affichage = [];
$total = 0;

if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $nom => $qte) {
        foreach ($catalogue as $p) {
            if ($p['nom'] === $nom) {
                $sous_total = $p['prix'] * $qte;
                $panier_affichage[] = [
                    'nom' => $qte . "x " . $nom,
                    'prix' => $sous_total
                ];
                $total += $sous_total;
            }
        }
    }
}

if (isset($_SESSION['packs'])) {
    foreach ($_SESSION['packs'] as $pays => $qte_pack) {
        $prix_pack_brut = 0;
        
        foreach ($catalogue as $p) {
            if ($p['pays'] === $pays) {
                $prix_pack_brut += $p['prix'];
            }
        }
        

        $prix_pack_net = $prix_pack_brut * 0.90;
        $sous_total_pack = $prix_pack_net * $qte_pack;

        $panier_affichage[] = [
            'nom' => $qte_pack . "x Pack Menu " . $pays . " (-10%)",
            'prix' => $sous_total_pack
        ];
        $total += $sous_total_pack;
    }
}

if (empty($panier_affichage)) {
    header("Location: menu.php?erreur=panier_vide");
    exit();
}

$transaction = uniqid();
$montant = number_format($total, 2, '.', '');
$vendeur = 'MI-2_B';
$retour = 'http://localhost:8000/php/retour_paiement.php';
$api_key = getAPIKey($vendeur); 
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Mon Panier - Tasty Country</title>
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
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Votre commande 🛒</legend>
                
                <?php foreach ($panier_affichage as $article): ?>
                    <div class="info-row">
                        <div class="label"><?php echo $article['nom']; ?></div>
                        <div class="value"><?php echo number_format($article['prix'], 2); ?> €</div>
                    </div>
                <?php endforeach; ?>

                <div class="info-row" style="border-top: 2px solid #00FFFF; margin-top: 10px; padding-top: 10px;">
                    <div class="label"><strong>TOTAL TTC :</strong></div>
                    <div class="value"><strong><?php echo number_format($total, 2); ?> €</strong></div>
                </div>

                <div class="info-row">
                    <div class="label">Pour :</div>
                    <div class="value"><?php echo htmlspecialchars($_SESSION['date_heure'] ?? 'Maintenant'); ?></div>
                </div>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST' style="margin-top: 20px;">
                    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                    <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
                    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                    <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
                    <input type='hidden' name='control' value='<?php echo $control; ?>'>
                    <input type='submit' class="btn-edit" value="Valider et payer">
                </form>

                <div style="display:flex; gap:10px; margin-top:15px;">
                    <form action="menu.php" style="flex:1;">
                        <button type="submit" class="btn-edit" style="width:100%; background-color: #555;">Retour au menu</button>
                    </form>
                    <form action="panier.php" method="GET" style="flex:1;">
                        <input type="hidden" name="action" value="vider">
                        <button type="submit" class="btn-edit" style="width:100%; background-color: #d32f2f;">Vider le panier</button>
                    </form>
                </div>
            </fieldset>
        </main>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tasty Country 🌍</h3>
                    <p>Le tour du monde dans votre assiette.</p>
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
