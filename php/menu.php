<?php
session_start();

$json_path = 'menu.json';
$plats_complets = [];

try {
    if (!file_exists($json_path)) {
        throw new Exception("Erreur système : Catalogue introuvable.");
    }
    
    $json_content = file_get_contents($json_path);
    $plats_complets = json_decode($json_content, true);
    
    if ($plats_complets === null) {
        throw new Exception("Erreur de lecture des données.");
    }
} 
catch (Exception $e) {
    $erreur_message = $e->getMessage();
}

$cat_choisie = $_GET['categorie'] ?? '';
$pays_choisi = $_GET['pays'] ?? '';

$plats = [];
foreach ($plats_complets as $p) {
    $match_cat = ($cat_choisie == '' || $p['categorie'] == $cat_choisie);
    $match_pays = ($pays_choisi == '' || $p['pays'] == $pays_choisi);

    if ($match_cat && $match_pays) {
        $plats[] = $p;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Menu - Tasty Country</title>
</head>
<body id="top">
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand"><h1>Tasty Country ✈️</h1></div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php" class="nav-active">Menu</a></li>
                        <?php if (isset($_SESSION['email'])): ?>
                            <li><a href="profil.php">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>
                        <?php else: ?>
                            <li><a href="inscription.php">Nous rejoindre</a></li>
                            <li><a href="connexion.php">Se connecter</a></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="menu-container">
            <h2 class="section-title">Nos Destinations Culinaires</h2>
            
            <?php if (isset($erreur_message)): ?>
                <p class="error-msg"><?= $erreur_message ?></p>
            <?php endif; ?>

            <section class="filters">
                <form method="GET" action="menu.php">
                    <select name="pays" onchange="this.form.submit()">
                        <option value="">Tous les Menus 🌍</option>
                        <option value="France" <?= ($pays_choisi == 'France') ? 'selected' : '' ?>>Menu Français 🇫🇷</option>
                        <option value="Italie" <?= ($pays_choisi == 'Italie') ? 'selected' : '' ?>>Menu Italien 🇮🇹</option>
                        <option value="Japon" <?= ($pays_choisi == 'Japon') ? 'selected' : '' ?>>Menu Japonais 🇯🇵</option>
                    </select>

                    <select name="categorie" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        <option value="entree" <?= ($cat_choisie == 'entree') ? 'selected' : '' ?>>Entrées</option>
                        <option value="plat" <?= ($cat_choisie == 'plat') ? 'selected' : '' ?>>Plats</option>
                        <option value="dessert" <?= ($cat_choisie == 'dessert') ? 'selected' : '' ?>>Desserts</option>
                    </select>
                    <a href="menu.php" class="reset-link">Reset</a>
                </form> 
            </section>

            <form action="panier.php" method="POST">
                
                <?php if ($pays_choisi != ''): ?>
                    <div class="menu-promo-banner">
                        <h3>🎁 Pack Destination : <?= htmlspecialchars($pays_choisi) ?></h3>
                        <p>Commandez le menu complet (Entrée + Plat + Dessert) et profitez de <strong>-10% de remise immédiate !</strong></p>
                        <button type="submit" name="pack_menu" value="<?= htmlspecialchars($pays_choisi) ?>" class="btn-pack">
                            Ajouter le Pack Menu (1 pers.)
                        </button>
                    </div>
                <?php endif; ?>

                <section class="product-grid">
                    <?php foreach ($plats as $p): ?>
                        <div class="product-card">
                            <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            <div class="product-info">
                                <h3>
                                    <?= htmlspecialchars($p['nom']) ?>
                                    <div class="info-container">
                                        <span class="info-icon">i</span>
                                        <div class="info-tooltip">
                                            <strong>Ingrédients :</strong><br>
                                            <?= htmlspecialchars(implode(', ', $p['ingredients'])) ?><br>
                                            <span class="allergenes-list">⚠️ Allergènes : <?= htmlspecialchars(implode(', ', $p['allergenes'])) ?></span>
                                        </div>
                                    </div>
                                </h3>
                                <p class="country-label"><?= htmlspecialchars($p['pays']) ?></p>
                                <span class="price"><?= number_format($p['prix'], 2) ?>€</span>
                                
                                <div class="quantity-selector">
                                    <label>Quantité :</label>
                                    <input type="number" name="qte[<?= htmlspecialchars($p['nom']) ?>]" value="0" min="0">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="order-options">
                    <h3>🕒 Planification du vol</h3>
                    <div class="timing-choice">
                        <input type="radio" name="timing" value="immediat" checked id="imm"> <label for="imm">Immédiat 🚀</label>
                        <input type="radio" name="timing" value="plus_tard" id="late"> <label for="late">Plus tard 📅</label>
                    </div>
                    <div class="date-picker">
                        <input type="datetime-local" name="date_heure" value="<?= date('Y-m-d\TH:i') ?>">
                    </div>
                    <button type="submit" class="add-btn">Valider mon Panier 💳</button>
                </section>
            </form>
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
                <p>&copy; <?= date('Y') ?> Tasty Country - Projet Informatique</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
