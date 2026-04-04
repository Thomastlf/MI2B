<?php
session_start();
require('../php/getapikey.php');
$transaction = $_GET['transaction'];
$montant = $_GET['montant'];
$vendeur = $_GET['vendeur'];
$status = $_GET['status']; 
$control_recu = $_GET['control'];
$api_key = getAPIKey($vendeur);
$control_calcule = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");
$titre_message = "";
$texte_message = "";



if ($control_calcule == $control_recu) {
    if ($status == 'accepted') {
        $titre_message = "Merci pour votre commande ! 🎉";
        $texte_message = "Votre paiement de " .$montant. " € a bien été accepté. Votre commande part en préparation !";  
        $email_recupere = $_SESSION['email'];
        $data = json_decode(file_get_contents("../json/utilisateur.json"), true);
        foreach ($data as $ligne){
            if ($email_recupere==$ligne['email']){
                $nom  = $ligne['nom'];
                $prenom    = $ligne['prenom'];
                $adresse = $ligne['adresse'];
                $code_interphone = $ligne['code_interphone'];
            }
        }
        $fichier = '../json/commande.json';
        $tab = [
            "id"  => $transaction,
            "client"    => $prenom." ".$nom,
            "date_heure"    => $_SESSION["date_heure"],
            "articles" => $_SESSION["panier"],
            "livreur" => null,
            "adresse" => $adresse,
            "code_interphone" => $code_interphone,
            "statut" => "a_preparer"
        ];
        if (file_exists($fichier)) {
            $contenu = file_get_contents($fichier);
            $utilisateurs = json_decode($contenu, true) ?? [];
        } else {
            $utilisateurs = [];
        }
        $utilisateurs[] = $tab;
        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));





    } else {
        $titre_message = "Paiement refusé ❌";
        $texte_message = "La transaction a échoué ou a été annulée. Veuillez réessayer.";
    }
} else {
    $titre_message = "Erreur de sécurité ⚠️";
    $texte_message = "Les données de la transaction sont corrompues ou invalides.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">// a modifier
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Résultat du paiement - Tasty Country</title>
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
            <fieldset class="profile-section">
                <legend>Statut de votre paiement</legend>
                <div style="text-align: center; padding: 20px;">
                    <h2><?php echo $titre_message; ?></h2>
                    <p><?php echo $texte_message; ?></p>
                    <form action="profil.php">
                        <button type="submit" class="btn-edit">Aller à mon profil</button>
                    </form>
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
