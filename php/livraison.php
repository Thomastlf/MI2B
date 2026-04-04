<?php
$json_path = '../json/commande.json';
$vols_a_livrer = [];

try {
    if (!file_exists($json_path)) { throw new Exception("Fichier introuvable"); }
    $data = json_decode(file_get_contents($json_path), true);
    foreach ($data as $c) {
        if (isset($c['livreur']) && $c['livreur'] == 'Marc' && $c['statut'] == 'en_livraison') {
            $vols_a_livrer[] = $c;
        }
    }
} catch (Exception $e) { $erreur = $e->getMessage(); }
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
                    <li><a href="profil.php">Mon Profil</a></li>
                    <li><a href="livraison.php" class="nav-active">Mes Vols</a></li>
                    <li><a href="connexion.php">Déconnexion</a></li>
                </ol></nav>
            </div>
        </header>
        <main class="content">
            <h2 class="page-title">Mes Livraisons en cours (Marc) 🚚</h2>
            <div class="orders-grid">
                <?php foreach ($vols_a_livrer as $v): ?>
                <div class="order-card">
                    <div class="order-header"><span class="order-id">#<?php echo $v['id']; ?></span></div>
                    <p><strong>Destination :</strong> <?php echo $v['adresse']; ?></p>
                    <p><strong>Articles :</strong> <?php echo implode(', ', $v['articles']); ?></p>
                    <div style="display:flex; gap:10px; margin-top:15px;">
                        <button class="btn-action" style="background:#2ecc71;">✅ Livrée</button>
                        <button class="btn-action" style="background:#e74c3c;">❌ Abandonnée</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
