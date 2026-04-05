<?php
session_start();

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: accueil.php");
    exit();
}

$json_path = '../json/avis_clients.json';
$avis = [];

if (file_exists($json_path)) {
    $avis = json_decode(file_get_contents($json_path), true);
    if (!empty($avis)) {
        $avis = array_reverse($avis);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css"> <link rel="stylesheet" href="../css/admin_avis.css"> <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Terminal Admin - Avis Clients</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                    <span class="badge-pro">TOUR DE CONTRÔLE</span>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="commande.php">Commandes</a></li>
                        <li><a href="admin.php">Gestion admin</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <h2 class="page-title">Manifeste des Avis Passagers 📋</h2>

            <div class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Passager</th>
                            <th>Note</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($avis)): ?>
                            <tr>
                                <td colspan="4" style="text-align:center; padding: 20px; color: white;">Aucun avis enregistré.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($avis as $a): ?>
                            <tr>
                                <td class="date-cell"><?php echo htmlspecialchars($a['date']); ?></td>
                                <td class="author-cell"><?php echo htmlspecialchars($a['auteur'] ?? 'Anonyme'); ?></td>
                                <td class="note-cell">
                                    <span class="stars-display">
                                        <?php 
                                        $n = intval($a['note']);
                                        for($i=1; $i<=5; $i++) {
                                            echo ($i <= $n) ? "★" : "☆";
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td class="comment-cell"><?php echo nl2br(htmlspecialchars($a['commentaire'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Terminal Administrateur CyTech</p>
                <a href="#top" style="color: #00FFFF; text-decoration: none; font-weight: bold; display: block; margin-top: 10px;">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
