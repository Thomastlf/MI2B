<?php
session_start();

$json_path = '../json/menu.json';

try {
    if (!file_exists($json_path)) {
        throw new Exception("Erreur système : Catalogue introuvable.");
    }
} 
catch (Exception $e) {
    $erreur_message = $e->getMessage();
}

$role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Menu - Tasty Country</title>
    
    <script src="../js/menu.js" defer></script>
</head>
<body id="top">
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand"><h1>Tasty Country ✈️</h1></div>
                <nav class="main-nav">
                    <ol>
                        <?php if (!isset($_SESSION['email'])): ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php" class="nav-active">Menu</a></li>
                            <li><a href="inscription.php">Nous rejoindre</a></li>
                            <li><a href="connexion.php">Se connecter</a></li>

                        <?php elseif ($role === 'admin'): ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php" class="nav-active">Menu</a></li>
                            <li><a href="commande.php">Commandes</a></li>
                            <li><a href="admin.php">Gestion Admin</a></li>
                            <li><a href="profil.php">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>

                        <?php else: ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php" class="nav-active">Menu</a></li>
                            <li><a href="profil.php">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>
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
                <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
                    
                    <select id="filtre-pays">
                        <option value="">Tous les Pays 🌍</option>
                        <option value="France">France 🇫🇷</option>
                        <option value="Italie">Italie 🇮🇹</option>
                        <option value="Japon">Japon 🇯🇵</option>
                        <option value="Vietnam">Vietnam 🇻🇳</option>
                        <option value="Espagne">Espagne 🇪🇸</option>
                        <option value="Maroc">Maroc 🇲🇦</option>
                        <option value="Suisse">Suisse 🇨🇭</option>
                    </select>

                    <select id="filtre-categorie">
                        <option value="">Toutes les catégories</option>
                        <option value="entree">Entrées</option>
                        <option value="plat">Plats</option>
                        <option value="dessert">Desserts</option>
                    </select>

                    <select id="filtre-allergene">
                        <option value="">Régime & Allergènes</option>
                        <option value="Gluten">Sans Gluten</option>
                        <option value="Lactose">Sans Lactose</option>
                        <option value="Oeuf">Sans Oeuf</option>
                    </select>

                    <select id="tri-prix">
                        <option value="">Trier par défaut</option>
                        <option value="prix_asc">Prix : Croissant</option>
                        <option value="prix_desc">Prix : Décroissant</option>
                    </select>
                    
                    <a href="menu.php" class="reset-link" style="padding: 10px; background:#e74c3c; color:white; border-radius:5px; text-decoration:none;">Réinitialiser</a>
                </div>
            </section>

            <form action="panier.php" method="POST">
                
                <div id="promo-banner-container"></div>

                <section class="product-grid" id="product-grid"></section>

                <section class="order-options">
                    <h3>🕒 Planification de la commande</h3>
                    <div class="timing-choice">
                        <input type="radio" name="timing" value="Maintenant" checked> 
                        <label for="imm">Maintenant 🚀</label>
                        
                        <input type="radio" name="timing" value="plus_tard" id="late"> 
                        <label for="late">Plus tard 📅</label>
                        
                        <div class="date-picker">
                            <input type="datetime-local" name="date_heure" value="<?= date('Y-m-d\TH:i') ?>">
                        </div>
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
