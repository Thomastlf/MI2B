<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$id_commande = $_GET['id'] ?? ($_POST['id_commande'] ?? null);

if (!$id_commande) {
    die("<h2 style='color:white; text-align:center; margin-top:50px;'>Erreur : Aucune commande spécifiée. ✈️</h2>");
}

$fichier_commandes = '../json/commande.json';
$commandes = file_exists($fichier_commandes) ? json_decode(file_get_contents($fichier_commandes), true) : [];

$commande_trouvee = null;
$index_commande = -1;

foreach ($commandes as $index => $cmd) {
    if ($cmd['id'] == $id_commande) {
        $commande_trouvee = $cmd;
        $index_commande = $index;
        break;
    }
}

// Blocages de sécurité
if (!$commande_trouvee) {
    die("<h2 style='color:white; text-align:center; margin-top:50px;'>Erreur : Commande introuvable. ❌</h2>");
}
if ($commande_trouvee['client'] !== $_SESSION['email']) {
    die("<h2 style='color:white; text-align:center; margin-top:50px;'>Erreur : Ce vol ne vous appartient pas. 🛑</h2>");
}
if ($commande_trouvee['statut'] !== 'livree') {
    die("<h2 style='color:white; text-align:center; margin-top:50px;'>Erreur : Ce vol n'est pas encore arrivé à destination (non livré). ⏳</h2>");
}

$deja_note = isset($commande_trouvee['avis']);

if (!empty($_POST) && !$deja_note) {
    $note = isset($_POST['star']) ? intval($_POST['star']) : 0;
    $commentaire = htmlspecialchars($_POST['commentaire']);
    
    $nouvel_avis = [
        "date" => date("Y-m-d H:i:s"),
        "note" => $note,
        "commentaire" => $commentaire
    ];

    // On sauvegarde l'avis dans la commande 
    $commandes[$index_commande]['avis'] = $nouvel_avis;
    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
    
    // On copie aussi l'avis dans avis_clients.json pour garder un historique propre pour l'admin
    $fichier_avis = '../json/avis_clients.json';
    $avisExistants = file_exists($fichier_avis) ? json_decode(file_get_contents($fichier_avis), true) : [];
    $avisExistants[] = array_merge(["id_commande" => $id_commande, "auteur" => $_SESSION['email']], $nouvel_avis);
    file_put_contents($fichier_avis, json_encode($avisExistants, JSON_PRETTY_PRINT));

    $deja_note = true;
    $messageSucces = "Merci ! Votre carnet de bord a été enregistré avec succès. ✈️";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/notation.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Notation - Tasty Country</title>
    
    <script src="../js/notation.js" defer></script>
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
            <?php if (isset($messageSucces)): ?>
                <div style="color: #00FFFF; text-align: center; margin-bottom: 20px; font-weight: bold; background: rgba(0,255,255,0.1); padding: 15px; border-radius: 8px;">
                    <?php echo $messageSucces; ?>
                </div>
            <?php endif; ?>

            <div class="rating-card">
                <h2>Votre Avis sur le Vol #<?php echo htmlspecialchars($id_commande); ?> ✈️</h2>
                
                <?php if ($deja_note): ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <p style="font-size: 1.2rem; color: #f1c40f;">⭐⭐⭐⭐⭐</p>
                        <p>Vous avez déjà noté cette commande !</p>
                        <a href="profil.php" class="btn-submit" style="display:inline-block; margin-top:20px; text-decoration:none;">Retour au profil</a>
                    </div>
                <?php else: ?>
                    <p>Comment s'est passée votre escale culinaire ?</p>
                    
                    <form id="form-notation" action="notation.php" method="POST">
                        <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($id_commande); ?>">
                        
                        <div class="stars">
                            <input type="radio" name="star" id="star5" value="5"><label for="star5">★</label>
                            <input type="radio" name="star" id="star4" value="4"><label for="star4">★</label>
                            <input type="radio" name="star" id="star3" value="3"><label for="star3">★</label>
                            <input type="radio" name="star" id="star2" value="2"><label for="star2">★</label>
                            <input type="radio" name="star" id="star1" value="1"><label for="star1">★</label>
                        </div>

                        <div class="input-group">
                            <label style="display: flex; justify-content: space-between;">
                                <span>Partagez votre expérience</span>
                                <span id="compteurCommentaire" style="font-size: 0.8em; color: gray;"></span>
                            </label>
                            <textarea id="commentaire" name="commentaire" maxlength="500" placeholder="Le voyage était magnifique..." rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">Envoyer mon carnet de bord</button>
                    </form>
                <?php endif; ?>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Projet Informatique</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
