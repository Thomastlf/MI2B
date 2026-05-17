<?php
session_start();
    $erreur="";
    if (!empty($_POST)) {
        $fichier = '../json/utilisateur.json';
        $data = [
            "nom"  => $_POST['nom'],
            "prenom"    => $_POST['prenom'],
            "email"    => $_POST['email'],
            "adresse" => $_POST['adresse'],
            "code_interphone" => $_POST['code_interphone'],
            "numero" => $_POST['numero'],
            "date"  => $_POST['date'],
            "genre"    => $_POST['genre'],
            "motdepasse"    => $_POST['motdepasse'],
            "role" => "client",
            "statut" => "actif",
            "niveau" => "Classique",
            "remise" => "0"
        ];

        if (file_exists($fichier)) {
            $contenu = file_get_contents($fichier);
            $utilisateurs = json_decode($contenu, true) ?? [];
        } else {
            $utilisateurs = [];
        }
        $email_existe = false;
        foreach ($utilisateurs as $utilisateur) {
            if ($utilisateur['email'] == $_POST['email']) {
                $email_existe = true;
                }
    }
        if(!$email_existe){
            $utilisateurs[] = $data;
            file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['role'] = "client";
            header('Location: http://localhost:8000/php/accueil.php'); 
        }
        else{
            $erreur="Erreur : Un utilisateur avec cette adresse email existe déjà.";
        }
    }
    $css="../css/connexion.css";/*js*/
    $texteBouton="Passer en sombre";
    if(isset($_COOKIE["modeSombre"]) && $_COOKIE["modeSombre"] == "true"){
        $css="../css_sombre/connexion.css";
        $texteBouton="Passer en clair";
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
    <link rel="stylesheet" href="../css/inscription.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Inscription - Tasty Country</title>
    <link id="css" rel="stylesheet" href=<?php echo $css; ?>><!-- js -->
    <script src="../js/theme.js" defer></script><!-- js / defer pour n'exécuter le script js qu'une fois que le navigateur aura chargé le html dans le dom -->
    <script src="../js/mdp.js" defer></script>
    <script src="../js/inscription.js" defer></script>
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
                        <li><a href="inscription.php" class="nav-active">Nous rejoindre</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
            <button id="bouton" class="btn_theme"><?php echo $texteBouton; ?></button><!-- js -->
        </header>

        <main class="content">
            <div class="register-card">
                <h2>Inscription Passager 📋</h2>
                <?php echo "<p style='color:red; text-align:center;'>$erreur</p>"; ?>
                <p id="erreur_js" style="color:red; text-align:center;"></p>
                <form id="envoyer" action="inscription.php" method="POST">
                    
                    <div class="input-group">
                        <label>Nom</label>
                        <input id="nom" type="text" name="nom" maxlength="50" placeholder="Votre nom">
                    </div>

                    <div class="input-group">
                        <label>Prénom</label>
                        <input id="prenom" type="text" name="prenom" maxlength="50" placeholder="Votre prénom">
                    </div>

                    <div class="input-group">
                        <label>E-mail</label>
                        <input id="email"type="text" name="email" placeholder="votre@email.com">
                    </div>

                    <div class="input-group">
                        <label>Adresse</label>
                        <input id="adresse" type="text" name="adresse" placeholder="Votre adresse complète">
                    </div>
                    
                    <div class="input-group">
                        <label>Code d'interphone (facultatif)</label>
                        <input type="text" name="code_interphone" placeholder="Votre code interphone">
                    </div>

                    <div class="input-group">
                        <label>Numéro de téléphone</label>
                        <input id="numero" type="text" name="numero" placeholder="06 12 34 56 78">
                    </div>

                    <div class="input-group">
                        <label>Date de naissance</label>
                        <input id="date" type="date" name="date">
                    </div>

                    <div class="input-group">
                        <label>Genre</label>
                        <select name="genre" required>
                            <option value="Homme">Homme</option>
                            <option value="Femme">Femme</option>
                            <option value="Je ne veux pas me positionner">Je ne veux pas me positionner</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Mot de passe</label>
                        <input id="mdp" type="password" name="motdepasse" placeholder="••••••••">
                        
                        <button type="button" id="bouton2" class="btn_oeil">👁️</button><!-- js on mets bien type="button" sinon il envoie le formulaire-->
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Envoyer" class="btn-submit">
                        <input type="reset" value="Réinitialiser" class="btn-reset">
                    </div>
                </form>
                <p class="form-footer">Déjà un billet ? <a href="connexion.php">Se connecter</a></p>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Projet Informatique CyTech</p>
                <a href="#top">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
