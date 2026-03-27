<?php 
session_start(); 
$json_path = 'commande.json';
$user_path = 'utilisateur.json';
$mes_vols = [];
$nom = $prenom = $email = $adresse = "Non connecté";

try {
    $email_session = $_SESSION['email'] ?? 'jean.dupont@gmail.com'; // Jean Dupont par défaut pour le test
    $users = json_decode(file_get_contents($user_path), true);
    foreach ($users as $u) {
        if ($u['email'] == $email_session) {
            $nom = $u['nom']; $prenom = $u['prenom']; $email = $u['email']; $adresse = $u['adresse'];
        }
    }
    $commandes = json_decode(file_get_contents($json_path), true);
    foreach ($commandes as $c) {
        if ($c['client'] == "Jean Dupont" || $c['client'] == ($prenom." ".$nom)) {
            $mes_vols[] = $c;
        }
    }
} catch (Exception $e) { }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="commande.css">
    <link rel="icon" type="image/png" href="Logo_Tasty_Country.png">
    <title>Mon Profil - Tasty Country</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand"><h1>Tasty Country ✈️</h1></div>
                <nav class="main-nav"><ol>
                    <li><a href="accueil.html">Accueil</a></li>
                    <li><a href="menu.html">Menu</a></li>
                    <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                </ol></nav>
            </div>
        </header>
        <main class="content">
            <h2 class="page-title">Bonjour, <?php echo $prenom; ?> 👋</h2>
            <div class="orders-grid">
                <section class="order-column">
                    <h3>👤 Mes Informations</h3>
                    <div class="order-card">
                        <p><strong>Nom :</strong> <?php echo $nom; ?></p>
                        <p><strong>Email :</strong> <?php echo $email; ?></p>
                        <p><strong>Adresse :</strong> <?php echo $adresse; ?></p>
                        <button class="btn-action">Modifier</button>
                    </div>
                </section>
                <section class="order-column" style="flex: 2;">
                    <h3>📦 Mon Historique</h3>
                    <?php foreach ($mes_vols as $v): ?>
                    <div class="order-card">
                        <div class="order-header"><span>#<?php echo $v['id']; ?></span><strong><?php echo $v['statut']; ?></strong></div>
                        <p><?php echo implode(', ', $v['articles']); ?></p>
                        <?php if ($v['statut'] == 'livree'): ?>
                            <a href="notation.php?id_commande=<?php echo $v['id']; ?>" class="btn-action" style="background:#f1c40f; color:black; text-decoration:none; display:inline-block; text-align:center;">⭐ Noter</a>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
