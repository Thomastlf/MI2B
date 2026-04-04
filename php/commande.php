<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: connexion.php");
    exit();
}

$role = strtolower($_SESSION['role']);

if ($role !== 'admin' && $role !== 'restaurateur') {
    header("Location: accueil.php");
    exit();
}

$json_path = '../json/commande.json';
$commandes = [];

try {
    if (!file_exists($json_path)) {
        throw new Exception("Le fichier de données est introuvable.");
    }
    $json_data = file_get_contents($json_path);
    $commandes = json_decode($json_data, true);
    
    if ($commandes === null) {
        throw new Exception("Erreur lors du décodage du fichier JSON.");
    }
} catch (Exception $e) {
    $erreur = $e->getMessage();
}

$a_preparer = [];
$en_livraison = [];
$livrees = [];

foreach ($commandes as $c) {
    if ($c['statut'] == 'a_preparer') {
        $a_preparer[] = $c;
    } elseif ($c['statut'] == 'en_livraison') {
        $en_livraison[] = $c;
    } elseif ($c['statut'] == 'livree') {
        $livrees[] = $c;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/commande.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Espace Restaurateur - Tasty Country</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                    <span class="badge-pro"><?php echo ($role === 'admin') ? 'ACCÈS ADMIN' : 'ACCÈS RESTAURATEUR'; ?></span>
                </div>
                <nav class="main-nav">
                    <ol>
                        <?php if ($role === 'admin'): ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php">Menu</a></li>
                            <li><a href="commande.php" class="nav-active">Commandes</a></li>
                            <li><a href="admin.php">Gestion Admin</a></li>
                            <li><a href="profil.php">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>

                        <?php elseif ($role === 'restaurateur'): ?>
                            <li><a href="commande.php" class="nav-active">Commandes</a></li>
                            <li><a href="profil.php">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <h2 class="page-title">Tableau de Bord des Commandes 👨‍🍳</h2>

            <?php if (isset($erreur)): ?>
                <p style="color: red; text-align: center;"><?php echo $erreur; ?></p>
            <?php endif; ?>

            <div class="orders-grid">
                <section class="order-column">
                    <h3>📦 Commandes à préparer</h3>
                    <?php foreach ($a_preparer as $cmd): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#<?php echo $cmd['id']; ?></span>
                                <span class="order-time"><?php echo $cmd['date_heure']; ?></span>
                            </div>
                            <p><strong>Passager :</strong> <?php echo $cmd['client']; ?></p>
                            <p>
                                <?php foreach ($cmd['articles'] as $article): ?>
                                    • <?php echo $article['quantite']; ?>x <?php echo $article['nom']; ?><br>
                                <?php endforeach; ?>
                            </p>
                            <button class="btn-action">Prêt pour la livraison ✈️</button>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="order-column delivery-section">
                    <h3>🚚 En cours de livraison</h3>
                    <?php foreach ($en_livraison as $cmd): ?>
                        <div class="order-card in-flight">
                            <div class="order-header">
                                <span class="order-id">#<?php echo $cmd['id']; ?></span>
                                <span class="status-tag">En vol</span>
                            </div>
                            <p>Livreur : <strong><?php echo $cmd['livreur']; ?></strong></p>
                            <p>Escale : <?php echo $cmd['adresse']; ?></p>
                            <button class="btn-disabled" disabled>Livraison en cours...</button>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="order-column history-section">
                    <h3>✅ Historique</h3>
                    <?php foreach ($livrees as $cmd): ?>
                        <div class="order-card" style="opacity: 0.7;">
                            <div class="order-header">
                                <span class="order-id">#<?php echo $cmd['id']; ?></span>
                                <span class="status-tag" style="background:#2ecc71;">Livré</span>
                            </div>
                            <p>Client : <?php echo $cmd['client']; ?></p>
                            <button class="btn-disabled" disabled>Archivé</button>
                        </div>
                    <?php endforeach; ?>
                </section>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tasty Country 🌍</h3>
                    <p>Terminal de gestion interne.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Projet CyTech</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
