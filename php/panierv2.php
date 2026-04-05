<?php
session_start();

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
if (!isset($_SESSION['packs'])) $_SESSION['packs'] = [];

if (isset($_POST['pack_menu'])) {
    $pays = $_POST['pack_menu'];
    
    if (isset($_SESSION['packs'][$pays])) {
        $_SESSION['packs'][$pays]++;
    } else {
        $_SESSION['packs'][$pays] = 1;
    }
    
    header("Location: menu.php?success=pack_ajoute");
    exit();
}

if (isset($_POST['qte']) && is_array($_POST['qte'])) {
    foreach ($_POST['qte'] as $nom => $qte) {
        $qte_int = (int)$qte;
        if ($qte_int > 0) {
            if (isset($_SESSION['panier'][$nom])) {
                $_SESSION['panier'][$nom] += $qte_int;
            } else {
                $_SESSION['panier'][$nom] = $qte_int;
            }
        }
    }

    if (isset($_POST['timing'])) {
        if ($_POST['timing'] === "plus_tard") {
            $_SESSION['date_heure'] = $_POST['date_heure'] ?? date('Y-m-d H:i:s');
        } else {
            $_SESSION['date_heure'] = "Maintenant";
        }
    }

    header("Location: commande.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'vider') {
    $_SESSION['panier'] = [];
    $_SESSION['packs'] = [];
    header("Location: menu.php");
    exit();
}

header("Location: menu.php");
exit();
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
                <legend>Votre panier 🛒</legend>
                
                <?php foreach ($panier as $article) { ?>
                    <div class="info-row">
                        <div class="label"><?php echo $article['quantite']; ?>x <?php echo $article['nom']; ?></div>
                        <div class="value"><?php echo number_format($article['sous_total'], 2); ?> €</div>
                    </div>
                <?php } ?>

                <div class="info-row">
                    <div class="label">TOTAL :</div>
                    <div class="value"><?php echo number_format($total, 2)."€ (-".number_format($reduction, 2)."€)"; ?></div>
                    <div class="value"><?php echo "Grade : " . htmlspecialchars($niveau); ?></div>
                    <div class="value"><?php echo "Niveau remise : " . htmlspecialchars($remise); ?></div>
                </div>

                <div class="info-row">
                    <div class="label">Pour :</div>
                    <div class="value">
                        <?php 
                        if (isset($_POST['timing']) && $_POST['timing'] == "Maintenant") {
                            echo "Maintenant";
                        } else {
                            echo htmlspecialchars($_SESSION['date_heure'] ?? 'Non défini');
                        } 
                        ?>
                    </div>
                </div>

                <form action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
                    <?php
                    $trouve = true;
                    $fichier = "../json/commande.json";
                    $commande = [];
                    if (file_exists($fichier)) {
                        $contenu = file_get_contents($fichier);
                        $commande = json_decode($contenu, true) ?? [];
                    }
                    
                    while ($trouve) {
                        $transaction = uniqid();
                        $trouve = false;
                        foreach ($commande as $c) {
                            if (isset($c['id']) && $c['id'] == $transaction) {
                                $trouve = true;
                            }                       
                        }
                    }
                    
                    $montant = number_format($total, 2, '.', ''); // Sécurité format Cybank
                    $vendeur = 'MI-2_B';
                    $retour = 'http://localhost:8000/php/retour_paiement.php';
                    $api_key = getAPIKey($vendeur); 
                    $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
                    ?>
                    <input type='hidden' name='transaction' value='<?php echo $transaction; ?>'>
                    <input type='hidden' name='montant' value='<?php echo $montant; ?>'>
                    <input type='hidden' name='vendeur' value='<?php echo $vendeur; ?>'>
                    <input type='hidden' name='retour' value='<?php echo $retour; ?>'>
                    <input type='hidden' name='control' value='<?php echo $control; ?>'>
                    <input type='submit' class="btn-edit" value="Valider et payer">
                </form>

                <form action="menu.php">
                    <button type="submit" class="btn-edit" style="margin-top: 10px; background-color: #555;">Retour au menu</button>
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
