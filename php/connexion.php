<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_connexion  = $_POST['email']  ?? "";
    $motdepasse_connexion = $_POST['motdepasse'] ?? "";
    
    $json_path = "../json/utilisateur.json";
    if (file_exists($json_path)) {
        $data = json_decode(file_get_contents($json_path), true);
        
        foreach ($data as $utilisateur) {
            if ($email_connexion == $utilisateur['email'] && $motdepasse_connexion == $utilisateur['motdepasse']) {

                if (isset($utilisateur['statut']) && strtolower($utilisateur['statut']) === 'Bloque') {
                    $error = "Votre compte est suspendu. Veuillez contacter l'administration.";
                    break; 
                }

                $_SESSION['email'] = $email_connexion;
                $_SESSION['role'] = $utilisateur['role'];

                switch (strtolower($utilisateur['role'])) {
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'livreur':
                        header("Location: livraison.php");
                        break;
                    case 'restaurateur':
                        header("Location: commande.php");
                        break;
                    case 'client':
                    default:
                        header("Location: accueil.php");
                        break;
                }
                exit();
            }
        }
        if (!isset($error)) {
            $error = "Identifiants incorrects";
        }
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
    <title>Connexion - Tasty Country</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/connexion.css"><!-- js -->
    <link id="css" rel="stylesheet" href=<?php echo $css; ?>><!-- js -->
    <script src="../js/theme.js" defer></script><!-- js / defer pour n'exécuter le script js qu'une fois que le navigateur aura chargé le html dans le dom -->
    <script src="../js/mdp.js" defer></script>
    <script src="../js/connexion.js" defer></script>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <h1>Tasty Country ✈️</h1>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="inscription.php">Nous rejoindre</a></li>
                        <li><a href="connexion.php" class="nav-active">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
            <button type="button" id="bouton" class="btn_theme"><?php echo $texteBouton; ?></button><!-- js -->
        </header>

        <main class="content">
            <div class="login-card">
                <h2>Connexion Passager</h2>
                <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
                <p id="erreur_js" style="color:red; text-align:center;"></p>
                <form id="envoyer" action="connexion.php" method="POST">
                    <div class="input-group">
                        <label>Email</label>
                        <input id="email" type="text" name="email" placeholder="votre@email.com">
                    </div>
                    <div class="input-group">
                        <label>Mot de passe</label>
                        <input id="mdp" type="password" name="motdepasse" placeholder="••••••••">
                        <button type="button" id="bouton2" class="btn_oeil">👁️</button><!-- js on mets bien type="button" sinon il envoie le formulaire-->
                    </div>
                    <button type="submit" class="btn-login">Embarquement</button>
                </form>
                <p class="form-footer">Pas encore de billet ? <a href="inscription.php">Inscrivez-vous ici</a></p>
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
                <a href="#top" style="color: #00FFFF; text-decoration: none; display: block; margin-top: 10px;">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
