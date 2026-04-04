<?php
session_start(); 
$json_path = '../json/commande.json';
if (isset($_POST['action'])) {
    $id_a_modifier = $_POST['id_commande'];
    $toutes_commandes = json_decode(file_get_contents($json_path), true);
    foreach ($toutes_commandes as &$cmd) {
        if ($cmd['id'] === $id_a_modifier) {
            if($_POST['action'] == 'livree'){
                $cmd['statut'] = 'livree';
            }
            else if($_POST['action'] == 'abandonnée'){
                $cmd['livreur'] = null;
                $cmd['statut'] = 'a_preparer';
            }
            else{
                $cmd['statut'] = 'en_livraison';
                $cmd['livreur']=$_SESSION['email'];
            }
        }
    }
    file_put_contents($json_path, json_encode($toutes_commandes, JSON_PRETTY_PRINT));
    header("Location: http://localhost:8000/php/livraison.php"); 
    exit();
}
$vols_a_livrer = [];


$tab = json_decode(file_get_contents("../json/commande.json"), true);
$commande=[];
foreach ($tab as $ligne){
    if ($ligne['livreur'] == $_SESSION['email'] && $ligne["statut"]=="en_livraison") {
        $commande[] = $ligne;
    }
}
$commande_sans=[];
foreach ($tab as $ligne){
    if ($ligne['livreur'] == null) {
        $commande_sans[] = $ligne;
    }
}


$historique=[];
foreach ($tab as $ligne){
    if ($ligne['livreur'] == $_SESSION['email'] && $ligne["statut"]=="livree") {
        $historique[] = $ligne;
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
                        <form method="POST" action="livraison.php">
                            <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                            <input type="hidden" name="action" value="livree">
                            <button class="btn-action" style="background:#2ecc71;">✅ Livrée</button>
                        </form>
                        <form method="POST" action="livraison.php">
                            <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                            <input type="hidden" name="action" value="abandonnée">
                            <button class="btn-action" style="background:#e74c3c;">❌ Abandonnée</button>
                        </form>
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
                        <form method="POST" action="livraison.php">
                            <input type="hidden" name="id_commande" value="<?php echo $c['id']; ?>">
                            <input type="hidden" name="action" value="en_livraison">
                            <button class="btn-action" style="background:#2ecc71;">✅ Livrer</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <h2 class="page-title">Historiques de commandes 🚚</h2>
            <div class="orders-grid">
                <?php foreach ($historique as $c): ?>
                <div class="order-card">
                    <div class="order-header"><span class="order-id">#<?php echo $c['id']; ?></span></div>
                    <p><strong>Destination :</strong> <?php echo $c['adresse']; ?></p>
                                    <li>
                                    <span>Commande n°<?php echo $c['id']; ?></span><br /> <span>Date : <?php echo $c['date_heure']; ?></span> 
                                            <div>
                                            <?php 
                                            foreach($c['articles'] as $article){
                                                echo $article['quantite']."x" .$article["nom"]."<br />";} ?>
                                </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
