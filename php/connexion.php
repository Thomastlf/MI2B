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

                // MODIFICATION : Vérification du statut bloqué
                if (isset($utilisateur['statut']) && strtolower($utilisateur['statut']) === 'bloqué') {
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Tasty Country</title>
    <link rel="stylesheet" href="../css/connexion.css">
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
        </header>

        <main class="content">
            <div class="login-card">
                <h2>Connexion Passager</h2>
                <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>
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
                <p class="form-footer">Pas encore de billet ? <a href="inscription.php">Inscrivez-vous ici</a></p>
            </div>
        </main>
    </div>
</body>
</html>
