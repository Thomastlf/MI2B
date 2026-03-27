<?php
session_start(); 
$json_path = 'menu.json'; 
$plats_complets = [];

if (file_exists($json_path)) {
    $json_content = file_get_contents($json_path); 
    $plats_complets = json_decode($json_content, true); 
}
if (!is_array($plats_complets)) { $plats_complets = []; }

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
    <link rel="icon" type="image/png" href="Logo_Tasty_Country.png">
    <title>Menu - Tasty Country</title>
    <style>
        .info-container { position: relative; display: inline-block; cursor: help; margin-left: 10px; }
        .info-icon { background: #00bcd4; color: white; border-radius: 50%; width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; }
        .info-tooltip { 
            visibility: hidden; width: 220px; background-color: #1a1a1a; color: #fff; text-align: left;
            border-radius: 8px; padding: 12px; position: absolute; z-index: 100; bottom: 125%; left: 50%;
            margin-left: -110px; opacity: 0; transition: opacity 0.3s; border: 1px solid #00bcd4; font-size: 0.85rem;
        }
        .info-container:hover .info-tooltip { visibility: visible; opacity: 1; }
        .allergenes-list { color: #ff6b6b; font-weight: bold; display: block; margin-top: 8px; border-top: 1px solid #444; padding-top: 5px; }
        .filters form { display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand"><h1>Tasty Country ✈️</h1></div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.html">Accueil</a></li>
                        <li><a href="menu.php" class="nav-active">Menu</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="connexion.html">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="menu-container">
            <h2 style="text-align:center; color:white;">Nos Destinations Culinaires</h2>
            
            <section class="filters">
                <form method="GET" action="menu.php">
                    <select name="pays" onchange="this.form.submit()">
                        <option value="">Tous les Menus 🌍</option>
                        <option value="France" <?php if($pays_choisi == 'France') echo 'selected'; ?>>Menu Français 🇫🇷</option>
                        <option value="Italie" <?php if($pays_choisi == 'Italie') echo 'selected'; ?>>Menu Italien 🇮🇹</option>
                        <option value="Japon" <?php if($pays_choisi == 'Japon') echo 'selected'; ?>>Menu Japonais 🇯🇵</option>
                    </select>

                    <select name="categorie" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        <option value="entree" <?php if($cat_choisie == 'entree') echo 'selected'; ?>>Entrées</option>
                        <option value="plat" <?php if($cat_choisie == 'plat') echo 'selected'; ?>>Plats</option>
                        <option value="dessert" <?php if($cat_choisie == 'dessert') echo 'selected'; ?>>Desserts</option>
                    </select>
                    <a href="menu.php" style="color:#00bcd4; text-decoration:none; align-self:center; font-size:0.9rem;">Reset</a>
                </form> 
            </section>

            <form action="panier.php" method="POST">
                <section class="product-grid">
                    <?php foreach ($plats as $p): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($p['img']); ?>" alt="<?php echo htmlspecialchars($p['nom']); ?>">
                        <div class="product-info">
                            <h3>
                                <?php echo htmlspecialchars($p['nom']); ?>
                                <div class="info-container">
                                    <span class="info-icon">i</span>
                                    <div class="info-tooltip">
                                        <strong>Ingrédients :</strong><br>
                                        <?php echo htmlspecialchars(implode(', ', $p['ingredients'])); ?>
                                        <span class="allergenes-list">⚠️ Allergènes : <?php echo htmlspecialchars(implode(', ', $p['allergenes'])); ?></span>
                                    </div>
                                </div>
                            </h3>
                            <p style="color: #00bcd4; font-size: 0.8rem; margin-top:-10px;"><?php echo $p['pays']; ?></p>
                            <span class="price"><?php echo number_format($p['prix'], 2); ?>€</span>
                            
                            <div style="margin: 15px 0;">
                                <label>Quantité :</label>
                                <input type="number" name="qte[<?php echo htmlspecialchars($p['nom']); ?>]" value="0" min="0" style="width: 50px;">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </section>

                <section class="order-options" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; margin-top: 40px; color: white;">
                    <h3>🕒 Planification du vol</h3>
                    <input type="radio" name="timing" value="immediat" checked id="imm"> Immédiat 🚀
                    <input type="radio" name="timing" value="plus_tard" id="late" style="margin-left:15px;"> Plus tard 📅
                    <div style="margin-top:10px;">
                        <input type="datetime-local" name="date_heure" value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    <button type="submit" class="add-btn" style="width: 100%; margin-top: 20px;">Valider mon Panier 💳</button>
                </section>
            </form>
        </main>
    </div>
</body>
</html>
