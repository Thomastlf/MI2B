<?php session_start();
$data = json_decode(file_get_contents("../json/menu.json"), true);
$panier = [];
$total = 0;
$timing = 'immediat';
$date_heure = '';

if (isset($_POST['qte'])) {
    $timing = $_POST['timing'];
    $date_heure = $_POST['date_heure'];
    foreach ($_POST['qte'] as $nom_plat => $quantite) {
        if ($quantite > 0) {
            foreach ($data as $plat) {
                if ($nom_plat == $plat['nom']) {
                    $prix = $plat['prix'];
                }
            }
            $panier[] = [
                'nom' => $nom_plat,
                'quantite' => $quantite,
                'prix' => $prix,
                'sous_total' => $prix * $quantite
            ];
            $total = $total + ($prix * $quantite);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">// a modifieer
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Profil - Tasty Country</title>
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
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Votre panier 🛒</legend>
                <?php foreach ($panier as $article) { ?>
                    <div class="info-row">
                        <div class="label"><?php echo $article['quantite']; ?>x <?php echo $article['nom']; ?></div>
                        <div class="value"><?php echo $article['sous_total']; ?> €</div>
                        </div>
                    <?php } ?>

                    <div class="info-row">
                        <div class="label">TOTAL :</div>
                        <div class="value"><?php echo $total; ?> €</div>
                    </div>

                    <div class="info-row">
                        <div class="label">Départ du vol :</div>
                        <div class="value"><?php echo $timing; ?> - <?php echo $date_heure; ?></div>
                    </div>

                    <form action="traitement_commande.php" method="POST">
                        <button type="submit" class="btn-edit">Confirmer la commande ✅</button>
                    </form>

                    <form action="menu.php">
                        <button type="submit" class="btn-edit">Retour au menu</button>
                    </form>
            </fieldset></main>


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
