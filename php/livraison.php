<?php
session_start(); 
$json_path = '../json/commande.json';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'livreur') {
    header("Location: connexion.php");
    exit();
}

if (isset($_POST['action'])&& isset($_POST['id_commande'])) {
    $id_a_modifier = $_POST['id_commande'];
    $toutes_commandes = json_decode(file_get_contents($json_path), true);
    foreach ($toutes_commandes as $index => $cmd) {
    if ($cmd['id'] == $id_a_modifier) {
        if ($_POST['action'] == 'livree') {
            $toutes_commandes[$index]['statut'] = 'livree';
        } 
        else if ($_POST['action'] == 'abandonnée') {
            $toutes_commandes[$index]['livreur'] = null;
            $toutes_commandes[$index]['statut'] = 'sans_livreur';
        } 
        else {
            $toutes_commandes[$index]['statut'] = 'en_livraison';
            $toutes_commandes[$index]['livreur'] = $_SESSION['email'];
        }
    }
}
    file_put_contents($json_path, json_encode($toutes_commandes, JSON_PRETTY_PRINT));
    header("Location: livraison.php"); 
    exit();
}

$tab = json_decode(file_get_contents($json_path), true);
$commande=[];
foreach ($tab as $ligne){
    if (isset($ligne['livreur']) && $ligne['livreur'] == $_SESSION['email'] && $ligne["statut"]=="en_livraison") {
        $commande[] = $ligne;
    }
}

$commande_sans=[];
foreach ($tab as $ligne){
    if (!isset($ligne['livreur']) || $ligne['livreur'] == null) {
        $commande_sans[] = $ligne;
    }
}

$historique=[];
foreach ($tab as $ligne){
    if (isset($ligne['livreur']) && $ligne['livreur'] == $_SESSION['email'] && $ligne["statut"]=="livree") {
        $historique[] = $ligne;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/livraison.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Espace Livreur - Tasty Country</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                    <span class="badge-pro">ESPACE LIVREUR 📦</span>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="livraison.php" class="nav-active">Mes Vols</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="delivery-container">
            <div class="delivery-content-wrapper" style="width: 100%; max-width: 600px;">
                
                <?php if (!empty($commande)): ?>
                    <?php foreach ($commande as $c): ?>
                    <div class="delivery-card">
                        <h2>📍 Prochaine Escale</h2>
                        <div class="info-block">
                            <p><strong>Commande :</strong> #<?php echo $c['id']; ?></p>
                            <p><strong>Passager :</strong> <?php echo htmlspecialchars($c['client']); ?></p>
                            <p><strong>Adresse :</strong> <?php echo htmlspecialchars($c['adresse']); ?></p>
                            <p><strong>Contenu :</strong><br>
                                <?php foreach($c['articles'] as $art): ?>
                                    • <?php echo htmlspecialchars($art['quantite']); ?>x <?php echo htmlspecialchars($art['nom']); ?><br>
                                <?php endforeach; ?>
                            </p>
                        </div>
                        <div class="actions">
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($c['adresse']); ?>" class="map-btn" target="_blank">Ouvrir l'itinéraire 🗺️</a>
                            <div style="display:flex; gap:10px; width:100%;">
                                <form method="POST" action="livraison.php" style="flex:1;">
                                    <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                                    <input type="hidden" name="action" value="livree">
                                    <button type="submit" class="finish-btn">CONFIRMER ✅</button>
                                </form>
                                <form method="POST" action="livraison.php" style="flex:1;">
                                    <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                                    <input type="hidden" name="action" value="abandonnée">
                                    <button type="submit" class="finish-btn" style="background: #e74c3c;">ABANDONNER ❌</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: white; text-align: center; margin-bottom: 20px;">Aucun vol en cours. Sélectionnez une commande ci-dessous.</p>
                <?php endif; ?>

                <h2 class="page-title" style="margin-top: 40px; font-size: 1.5rem; color: #00FFFF;">📦 Vols disponibles</h2>
                <div class="orders-grid" style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($commande_sans as $c): ?>
                    <div class="order-card" style="background: rgba(33, 59, 97, 0.8); border: 1px solid #00FFFF; border-radius: 15px; padding: 15px; color: white;">
                        <p><strong>#<?php echo $c['id']; ?></strong> - <?php echo htmlspecialchars($c['adresse']); ?></p>
                        <form method="POST" action="livraison.php">
                            <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                            <input type="hidden" name="action" value="en_livraison">
                            <button type="submit" class="finish-btn" style="padding: 8px; font-size: 0.9rem; margin-top: 10px;">Prendre le vol ✈️</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <h2 class="page-title" style="margin-top: 40px; font-size: 1.5rem; color: #00FFFF;">📦 Historique des vols</h2>
                <div class="orders-grid" style="display: flex; flex-direction: column; gap: 15px;">
                    <?php foreach ($historique as $c): ?>
                    <div class="order-card" style="background: rgba(33, 59, 97, 0.8); border: 1px solid #00FFFF; border-radius: 15px; padding: 15px; color: white;">
                        <p><strong>#<?php echo $c['id']; ?></strong> - <?php echo htmlspecialchars($c['adresse']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tasty Country 🌍</h3>
                    <p>Terminal de livraison sécurisé.</p>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <p>📍 CyTech, Cergy</p>
                    <p>📞 Ligne interne : 01 23 45 67 89</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2026 Tasty Country - Projet Informatique</p>
                <a href="#top" style="color: #00FFFF; text-decoration: none; display: block; margin-top: 10px;">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
