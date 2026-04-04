<?php
session_start(); 
$json_path = '../json/commande.json';
$vols_a_livrer = [];


$tab = json_decode(file_get_contents("../json/commande.json"), true);
$commande=[];
foreach ($tab as $ligne){
    if ($ligne['livreur'] == $_SESSION['email']) {
        $commande[] = $ligne;
    }
}
$commande_sans=[];
foreach ($tab as $ligne){
    if ($ligne['livreur'] == null) {
        $commande_sans[] = $ligne;
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
    <title>Espace Livreur - Marc</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand"><h1>Tasty Country ✈️</h1><span class="badge-pro">ESPACE LIVREUR</span></div>
                <nav class="main-nav"><ol>
                    <li><a href="livraison.php" class="nav-active">Mes Vols</a></li>
                    <li><a href="profil.php">Mon Profil</a></li>
                    <li><a href="connexion.php">Déconnexion</a></li>
                </ol></nav>
            </div>
        </header>
        <main class="content">
            <h2 class="page-title">Mes Livraisons en cours (Marc) 🚚</h2>
            <div class="orders-grid">
                <?php foreach ($commande as $c): ?>
                <div class="order-card">
                    <div class="order-header"><span class="order-id">#<?php echo $c['id']; ?></span></div>
                    <p><strong>Destination :</strong> <?php echo $c['adresse']; ?></p>
                                    <li>
                                    <span>Commande n°<?php echo $c['id']."<br />"; ?></span> 
                                            <div>
                                            <?php 
                                            foreach($c['articles'] as $article){
                                                echo $article['quantite']."x" .$article["nom"]."<br />";} ?>
                                </div>
                    <div style="display:flex; gap:10px; margin-top:15px;">
                        <button class="btn-action" style="background:#2ecc71;">✅ Livrée</button>
                        <button class="btn-action" style="background:#e74c3c;">❌ Abandonnée</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <h2 class="page-title">Commandes sans livreur 🚚</h2>
            <div class="orders-grid">
                <?php foreach ($commande_sans as $c): ?>
                <div class="order-card">
                    <div class="order-header"><span class="order-id">#<?php echo $c['id']; ?></span></div>
                    <p><strong>Destination :</strong> <?php echo $c['adresse']; ?></p>
                                    <li>
                                    <span>Commande n°<?php echo $c['id']; ?></span> 
                                            <div>
                                            <?php 
                                            foreach($c['articles'] as $article){
                                                echo $article['quantite']."x" .$article["nom"]."<br />";} ?>
                                </div>
                    <div style="display:flex; gap:10px; margin-top:15px;">
                        <button class="btn-action" style="background:#2ecc71;">✅ Livrer</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
