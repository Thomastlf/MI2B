<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/inscription.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Inscription - Tasty Country</title>
</head>
<body>
    <?php
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
            "status" => ""
        ];
        if (file_exists($fichier)) {
            $contenu = file_get_contents($fichier);
            $utilisateurs = json_decode($contenu, true) ?? [];
        } else {
            $utilisateurs = [];
        }
        $utilisateurs[] = $data;
        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
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
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="inscription.php" class="nav-active">Nous rejoindre</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <div class="register-card">
                <h2>Inscription Passager 📋</h2>
                <form action="inscription.php" method="POST">
                    
                    <div class="input-group">
                        <label>Nom</label>
                        <input type="text" name="nom" maxlength="50" required placeholder="Votre nom">
                    </div>

                    <div class="input-group">
                        <label>Prénom</label>
                        <input type="text" name="prenom" maxlength="50" required placeholder="Votre prénom">
                    </div>

                    <div class="input-group">
                        <label>E-mail</label>
                        <input type="email" name="email" required placeholder="votre@email.com">
                    </div>

                    <div class="input-group">
                        <label>Adresse</label>
                        <input type="text" name="adresse" required placeholder="Votre adresse complète">
                    </div>
                    
                    <div class="input-group">
                        <label>Code d'interphone (facultatif)</label>
                        <input type="text" name="code_interphone" placeholder="Votre code interphone">
                    </div>

                    <div class="input-group">
                        <label>Numéro de téléphone</label>
                        <input type="tel" name="numero" required placeholder="06 12 34 56 78">
                    </div>

                    <div class="input-group">
                        <label>Date de naissance</label>
                        <input type="date" name="date" required>
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
                        <input type="password" name="motdepasse" required placeholder="••••••••">
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Envoyer" class="btn-submit">
                        <input type="reset" value="Réinitialiser" class="btn-reset">
                    </div>
                </form>
                <p class="form-footer">Déjà un billet ? <a href="connexion.html">Se connecter</a></p>
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
