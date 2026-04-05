<?php
session_start(); // Permet de garder la session active

// Vérifie si le formulaire a été soumis
if (!empty($_POST)) {
    // Récupération et sécurisation des données
    $note = isset($_POST['star']) ? $_POST['star'] : "Non précisée";
    $commentaire = htmlspecialchars($_POST['commentaire']);
    $date = date("Y-m-d H:i:s");
    $auteur = isset($_SESSION['email']) ? $_SESSION['email'] : "Anonyme";

    // Préparation des données pour le stockage JSON
    $nouvelAvis = [
        "date" => $date,
        "auteur" => $auteur, // On ajoute l'auteur pour savoir qui a noté
        "note" => $note,
        "commentaire" => $commentaire
    ];

    // Chargement des avis existants ou création d'un tableau vide
    $fichier = '../json/avis_clients.json';
    $avisExistants = [];
    if (file_exists($fichier)) {
        $avisExistants = json_decode(file_get_contents($fichier), true);
    }

    // Ajout du nouvel avis et sauvegarde
    $avisExistants[] = $nouvelAvis;
    file_put_contents($fichier, json_encode($avisExistants, JSON_PRETTY_PRINT));
    
    // Message de confirmation
    $messageSucces = "Merci ! Votre carnet de bord a été enregistré. ✈️";
}

// Récupération du rôle pour le header dynamique
$role = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/notation.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Notation - Tasty Country</title>
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
            <?php if (isset($messageSucces)): ?>
                <div style="color: #00FFFF; text-align: center; margin-bottom: 20px; font-weight: bold; background: rgba(0,255,255,0.1); padding: 10px; border-radius: 8px;">
                    <?php echo $messageSucces; ?>
                </div>
            <?php endif; ?>

            <div class="rating-card">
                <h2>Votre Avis sur le Vol ✈️</h2>
                <p>Comment s'est passée votre escale culinaire ?</p>
                
                <form action="notation.php" method="POST">
                    <div class="stars">
                        <input type="radio" name="star" id="star5" value="5"><label for="star5">★</label>
                        <input type="radio" name="star" id="star4" value="4"><label for="star4">★</label>
                        <input type="radio" name="star" id="star3" value="3"><label for="star3">★</label>
                        <input type="radio" name="star" id="star2" value="2"><label for="star2">★</label>
                        <input type="radio" name="star" id="star1" value="1"><label for="star1">★</label>
                    </div>

                    <div class="input-group">
                        <label>Partagez votre expérience</label>
                        <textarea name="commentaire" placeholder="Le voyage était magnifique..." rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Envoyer mon carnet de bord</button>
                </form>
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
