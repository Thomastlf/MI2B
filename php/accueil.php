<?php
session_start();

// Simulation de récupération du prénom si l'utilisateur est connecté
$prenom_utilisateur = "";
if (isset($_SESSION['email'])) {
    $email_session = $_SESSION['email'];
    $users = json_decode(file_get_contents("../json/utilisateur.json"), true);
    foreach ($users as $u) {
        if ($u['email'] == $email_session) {
            $prenom_utilisateur = $u['prenom'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/accueil.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Tasty Country - Accueil</title>
</head>
<body>
    <div class="site-container"> 
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php" class="nav-active">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        
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

        <main class="content">
            <h2 class="section-title">
                <?php 
                if ($prenom_utilisateur) {
                    echo "Bienvenue à bord, " . htmlspecialchars($prenom_utilisateur) . " ! 🌟";
                } else {
                    echo "À l'affiche ce mois-ci 🌟";
                }
                ?>
            </h2>
            
            <div class="featured-grid">
                <div class="product-card">
                    <img src="../img/japon.png" alt="Ramen">
                    <div class="product-info">
                        <h3>Ramen Croustillant</h3>
                        <span class="price">14.50€</span>
                        <a href="menu.php" class="add-btn">Voir au menu</a>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../img/italie.jpg" alt="Pasta">
                    <div class="product-info">
                        <h3>Parmesan Pasta</h3>
                        <span class="price">13.90€</span>
                        <a href="menu.php" class="add-btn">Voir au menu</a>
                    </div>
                </div>
            </div>
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
                <p>&copy; 2026 Tasty Country - Projet Informatique</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
