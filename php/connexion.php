<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion.css">
    <link rel="icon" type="image/png" href="Logo_Tasty_Country.png">
    <title>Connexion - Tasty Country</title>
</head>
<body>
    <?php
		$email_connexion  = $_POST['email']  ?? "";
		$motdepasse_connexion = $_POST['motdepasse'] ?? "";
        $data    = json_decode(file_get_contents("utilisateur.json"), true);
        foreach ($data as $utilisateur){
            $email_fichier  = $utilisateur['email'];
            $motdepasse_fichier    = $utilisateur['motdepasse'];
            if ($email_connexion==$email_fichier && $motdepasse_connexion==$motdepasse_fichier){
                header("Location: http://localhost:8000/accueil.php");
            }
        }
    ?>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.html">Accueil</a></li>
                        <li><a href="menu.html">Menu</a></li>
                        <li><a href="inscription.html">Nous rejoindre</a></li>
                        <li><a href="connexion.html" class="nav-active">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <div class="login-card">
                <h2>Connexion Passager</h2>
                <form action="connexion.php" method="POST">

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="votre@email.com" required>
                    </div>

                    <div class="input-group">
                        <label>Mot de passe</label>
                        <input type="password" name="motdepasse" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn-login">Embarquement</button>

                </form>
                <p class="form-footer">Pas encore de billet ? <a href="inscription.html">Inscrivez-vous ici</a></p>
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
