<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <link rel="icon" type="image/png" href="Logo_Tasty_Country.png">
    <title>Profil - Tasty Country</title>
</head>
<body>
    <?php $email_recupere = $_SESSION['email']; ?>
    <?php
        $data = json_decode(file_get_contents("utilisateur.json"), true);
        foreach ($data as $ligne){
            if ($email_recupere==$ligne['email']){
                $nom  = $ligne['nom'];
                $prenom    = $ligne['prenom'];
                $email    = $ligne['email'];
                $adresse = $ligne['adresse'];
                $code_interphone = $ligne['code_interphone'];
                $numero  = $ligne['numero'];
                $date    = $ligne['date'];
                $genre    = $ligne['genre'];
                $motdepasse = $ligne['motdepasse'];
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
                        <li><a href="connexion.html">Se connecter</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Vos informations 👤</legend>
                <div class="info-row">
                    <div class="label">Nom :</div>
                    <div class="value"><?php echo $nom; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Prénom :</div>
                    <div class="value"><?php echo $prenom; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">E-mail :</div>
                    <div class="value"><?php echo $email; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Adresse :</div>
                    <div class="value"><?php echo $adresse; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Code interphone :</div>
                    <div class="value"><?php echo $code_interphone; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Téléphone :</div>
                    <div class="value"><?php echo $numero; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Date de naissance :</div>
                    <div class="value"><?php echo $date; ?></div>
                </div>
                <div class="info-row">
                    <div class="label">Genre :</div>
                    <div class="value"><?php echo $genre; ?></div>
                </div>
                <button class="btn-edit" title="Modifier">Modifier les informations &#9998;</button>
            </fieldset>

            <fieldset class="profile-section">
                <legend>Vos anciennes commandes 📦</legend>
                <ul class="order-list">
                    <li>
                        <span class="order-dest">Indien</span>
                        <span class="order-price">15.95€</span>
                        <span class="order-date">15/05/2025</span>
                    </li>
                    <li>
                        <span class="order-dest">Chinois</span>
                        <span class="order-price">14.00€</span>
                        <span class="order-date">20/04/2023</span>
                    </li>
                </ul> 
            </fieldset>

            <fieldset class="profile-section">
                <legend>Compte fidélité 🎖️</legend>
                <div class="info-row">
                    <div class="label">Points de fidélité :</div>
                    <div class="value loyalty-points">564 pts</div>
                </div>
                <div class="promo-section">
                    <div class="label">Remises disponibles :</div>
                    <ul class="promo-list">
                        <li>-10% sur la prochaine commande</li>
                        <li>Une boisson offerte sur la prochaine commande</li>
                    </ul>
                </div>
            </fieldset>
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
