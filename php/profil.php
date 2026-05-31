<?php session_start(); 
if (isset($_SESSION['email'])) {
    $data = json_decode(file_get_contents("../json/utilisateur.json"), true);
    foreach ($data as $ligne) {
        if ($_SESSION['email'] == $ligne['email'] && $ligne['statut'] == 'Bloque') {
            session_destroy();
            header("Location: accueil.php");
            exit();
        }
    }
}
if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/php/accueil.php"); 
    exit();
}

if (isset($_GET['email']) && !empty($_GET['email'])) {
    $email_a_afficher = $_GET['email'];
} else {
    $email_a_afficher = $_SESSION['email'];
}

$role_session = isset($_SESSION['role']) ? strtolower($_SESSION['role']) : 'client';


$data = json_decode(file_get_contents("../json/utilisateur.json"), true);
        $profil_trouve = false;
        
        foreach ($data as $ligne){
            if ($email_a_afficher == $ligne['email']){
                $nom  = $ligne['nom'];
                $prenom    = $ligne['prenom'];
                $email    = $ligne['email'];
                $adresse = $ligne['adresse'];
                $code_interphone = $ligne['code_interphone'];
                $numero  = $ligne['numero'];
                $motdepasse = $ligne['motdepasse'];
                $role = $ligne['role'];
                $profil_trouve = true;
                $niveau = $ligne["niveau"] ?? "Classique";
                $remise = $ligne["remise"] ?? 0;
            }
        }

        if (!$profil_trouve) {
            echo "<p style='color:white; text-align:center; margin-top:50px;'>Utilisateur introuvable.</p>";
            exit();
        }
        $tab = json_decode(file_get_contents("../json/commande.json"), true);
        $commande=[];
        foreach ($tab as $ligne){
            if ($ligne['client'] == $email_a_afficher) {
                $commande[] = $ligne;
            }
        }

$css="";
$texteBouton="Passer en mode malvoyant";
if(isset($_COOKIE["theme"]) && $_COOKIE["theme"] == "true"){
    $css="../css/theme.css";
    $texteBouton="Passer en mode par défaut";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perso.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <link id="css" rel="stylesheet" href=<?php echo $css; ?>><!-- js -->
    <title>Profil - Tasty Country</title>
    <script src="../js/profil.js" defer></script>
    <script src="../js/theme.js" defer></script><!-- js / defer pour n'exécuter le script js qu'une fois que le navigateur aura chargé le html dans le dom -->
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
                        <?php if ($role_session === 'client'): ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php">Menu</a></li>
                            <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>

                        <?php elseif ($role_session === 'admin'): ?>
                            <li><a href="accueil.php">Accueil</a></li>
                            <li><a href="menu.php">Menu</a></li>
                            <li><a href="commande.php">Commandes</a></li>
                            <li><a href="admin.php">Gestion Admin</a></li>
                            <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>

                        <?php elseif ($role_session === 'restaurateur'): ?>
                            <li><a href="commande.php">Commandes en cours</a></li>
                            <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>

                        <?php elseif ($role_session === 'livreur'): ?>
                            <li><a href="livraison.php">Livraisons</a></li>
                            <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                            <li><a href="deconnexion.php">Déconnexion</a></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
            <button type="button" id="bouton" class="btn_theme"><?php echo $texteBouton; ?></button><!-- js -->
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Vos informations 👤</legend>
                <div class="info-row">
                    <div class="label">Nom :</div>
                    <div id="nom" class="value"><?php echo $nom; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Prénom :</div>
                    <div id="prenom" class="value"><?php echo $prenom; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">E-mail :</div>
                    <div id="email" class="value"><?php echo $email; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Adresse :</div>
                    <div id="adresse" class="value"><?php echo $adresse; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Code interphone :</div>
                    <div id="code" class="value"><?php echo $code_interphone; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Téléphone :</div>
                    <div id="numero" class="value"><?php echo $numero; ?></div>
                </div>
    <button type="button" id="bouton" class="btn-edit" onclick="afficherFormulaire()">
        Modifier mes informations 🖍️
                        </button>
    <div id="formulaire" style="display:none;"><div><!-- on cache le formulaire si l'on ne veut pas modifier les infos -->
        <div class="input-group">
                        <label>Nom</label>
                        <input id="nom2" type="text" name="nom" maxlength="50" placeholder="Votre nom">
                    </div>

                    <div class="input-group">
                        <label>Prénom</label>
                        <input id="prenom2" type="text" name="prenom" maxlength="50" placeholder="Votre prénom">
                    </div>

                    

                    <div class="input-group">
                        <label>Adresse</label>
                        <input id="adresse2" type="text" name="adresse" placeholder="Votre adresse complète">
                    </div>
                    
                    <div class="input-group">
                        <label>Code d'interphone (facultatif)</label>
                        <input id="code2" type="text" name="code_interphone" placeholder="Votre code interphone">
                    </div>

                    <div class="input-group">
                        <label>Numéro de téléphone</label>
                        <input id="numero2" type="text" name="numero" placeholder="06 12 34 56 78">
                    </div>

                    </div>

        <p id="erreur_js" style="color:red; text-align:center;"></p>
        <button type="button" class="btn-edit" onclick="validerModif()">Valider</button>
    </div>
            </fieldset>
            
            <?php if (strtolower($role) == 'client' || strtolower($role) == 'admin'): ?>
            <fieldset class="profile-section">
                <legend>Historique des vols 📦</legend>
                <ul class="order-list">
                    <?php if (empty($commande)): ?>
                        <li>Ce passager n'a pas encore de commandes.</li>
                    <?php else: ?>
                        <?php foreach ($commande as $c): ?>
                            <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 10px; gap: 10px;">
                                <div style="flex-grow: 1;">
                                    <strong>Commande n°<?php echo $c['id']; ?></strong><br>
                                    <span>Date : <?php echo $c['date_heure']; ?></span><br>
                                    <div style="font-size: 0.85rem; color: #555; margin: 5px 0;">
                                        <?php 
                                        foreach($c['articles'] as $article){
                                            echo htmlspecialchars($article['quantite']."x " .$article["nom"]." - ".$article['prix']."€")."<br>";
                                        } ?>
                                    </div>
                                    <span>Total : <?php echo $c["total"]; ?>€</span> | 
                                    <span>Statut : <?php echo ucfirst($c['statut']); ?></span>
                                    
                                    <?php if ($c['statut'] === 'a_preparer'): ?>
                                        <br>
                                        <a href="modifier_commande.php?id=<?php echo $c['id']; ?>" class="btn-edit" style="display:inline-block; text-align:center; text-decoration:none; width:auto; padding: 6px 12px; margin-top: 8px; font-size: 0.85rem; background-color: #00FFFF; color: #1D3557;">
                                            Modifier ma commande ✏️
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <a href="notation.php?id=<?php echo $c['id']; ?>" class="btn-noter-mini" title="Noter ce vol">Noter</a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </fieldset>
            <?php endif; ?>

            <?php if (strtolower($role) == 'client' || strtolower($role) == 'admin'): ?>
            <fieldset class="profile-section">
                <legend>Compte fidélité 🎖️</legend>
                <div class="info-row">
                    <div class="label">Grade :</div>
                    <div class="value loyalty-points"><?php echo $niveau; ?></div>
                </div>
                <div class="promo-section">
                    <div class="label">Remise niveau :</div>
                    <div class="value loyalty-points"><?php echo $remise; ?> %</div>
                </div>
            </fieldset>
            <?php endif; ?>
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
