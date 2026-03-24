<?php
// 1. Lecture des données (Exigence Phase 2 : arborescence distincte)
// Assure-toi que le fichier est bien dans un dossier 'data'
$json_path = 'commande.json'; 

if (file_exists($json_path)) {
    $json_data = file_get_contents($json_path);
    $commandes = json_decode($json_data, true);
} else {
    $commandes = []; 
}

// 2. Filtrage des commandes par statut
$a_preparer = array_filter($commandes, function($c) { 
    return isset($c['statut']) && $c['statut'] == 'a_preparer'; 
});

$en_livraison = array_filter($commandes, function($c) { 
    return isset($c['statut']) && $c['statut'] == 'en_livraison'; 
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="commande.css">
    <link rel="icon" type="image/png" href="Logo_Tasty_Country.png">
    <title>Espace Restaurateur - Tasty Country</title>
</head>
<body id="top"> <div class="site-container">
        
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                    <span class="badge-pro">ACCÈS RESTAURATEUR</span>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.html">Accueil</a></li>
                        <li><a href="commande.php" class="nav-active">Commandes</a></li>
                        <li><a href="admin.html">Gestion Utilisateurs</a></li>
                        <li><a href="connexion.html">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <h2 class="page-title">Tableau de Bord des Vols (Commandes) 👨‍🍳</h2>
            
            <div class="orders-grid">
                <section class="order-column">
                    <h3>📦 Commandes à préparer</h3>
                    <?php if (!empty($a_preparer)): ?>
                        <?php foreach ($a_preparer as $cmd): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#<?php echo ($cmd['id']); ?></span>
                                <span class="order-time"><?php echo ($cmd['date_heure']); ?></span>
                            </div>
                            <p><strong>Passager :</strong> <?php echo ($cmd['client']); ?></p>
                            <p><?php echo implode('<br>', array_map('htmlspecialchars', $cmd['articles'])); ?></p>
                            <p><strong>Paiement :</strong> <?php echo ($cmd['paiement'] ?? 'En attente'); ?></p>
                            <button class="btn-action">Prêt pour la livraison ✈️</button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: white; text-align: center;">Aucun vol en attente au terminal.</p>
                    <?php endif; ?>
                </section>

                <section class="order-column delivery-section">
                    <h3>🚚 En cours de livraison</h3>
                    <?php if (!empty($en_livraison)): ?>
                        <?php foreach ($en_livraison as $cmd): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#<?php echo ($cmd['id']); ?></span>
                                <span class="status-tag">En vol</span>
                            </div>
                            <p>Livreur : <strong><?php echo ($cmd['livreur'] ?? 'Pilote automatique'); ?></strong></p>
                            <p>Escale : <?php echo ($cmd['adresse']); ?></p>
                            <button class="btn-disabled" disabled>Livraison en cours...</button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: white; text-align: center;">Aucun vol en cours actuellement.</p>
                    <?php endif; ?>
                </section>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tasty Country 🌍</h3>
                    <p>Terminal de gestion interne.</p>
                </div>
                <div class="footer-section">
                    <h4>Assistance Pro</h4>
                    <p>📍 CyTech, Cergy</p>
                    <p>📞 01 23 45 67 89 (Ligne Directe)</p> </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Projet Informatique CyTech</p>
                <a href="#top">Revenir en haut ✈️</a> </div>
        </footer>
    </div>
</body>
</html>