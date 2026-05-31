<?php
session_start();
if (isset($_SESSION['email'])) {
    $data = json_decode(file_get_contents("../json/utilisateur.json"), true);
    foreach ($data as $ligne) {
        if ($_SESSION['email'] == $ligne['email'] && $ligne['statut'] == 'Bloqué') {
            session_destroy();
            header("Location: accueil.php");
            exit();
        }
    }
}
require('../php/getapikey.php');

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$status = $_GET['status'] ?? ''; 
$control_recu = $_GET['control'] ?? '';

$api_key = getAPIKey($vendeur);
$control_calcule = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

$titre_message = "";
$texte_message = "";

if ($control_calcule == $control_recu) {
    if ($status == 'accepted') {
        $titre_message = "Modification validée ! 🎉";
        $texte_message = "Le supplément de " .$montant. " € a bien été accepté. Votre commande a été mise à jour !";  
        
        if (isset($_SESSION['modif_en_attente'])) {
            $id_commande = $_SESSION['modif_en_attente']['id_commande'];
            $nouveaux_articles = $_SESSION['modif_en_attente']['nouveaux_articles'];
            $nouveau_total = $_SESSION['modif_en_attente']['nouveau_total'];

            $fichier = '../json/commande.json';
            if (file_exists($fichier)) {
                $commandes = json_decode(file_get_contents($fichier), true) ?? [];
                
                foreach ($commandes as $index => $cmd) {
                    if ($cmd['id'] == $id_commande && $cmd['client'] == $_SESSION['email']) {
                        $commandes[$index]['articles'] = $nouveaux_articles;
                        $commandes[$index]['total'] = $nouveau_total;
                        break;
                    }
                }
                
                file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));
            }
            
            unset($_SESSION['modif_en_attente']);
        }

    } else {
        $titre_message = "Paiement refusé ❌";
        $texte_message = "La transaction pour la modification a échoué. Votre commande initiale reste inchangée.";
        unset($_SESSION['modif_en_attente']);
    }
} else {
    $titre_message = "Erreur de sécurité ⚠️";
    $texte_message = "Les données de la transaction sont corrompues ou invalides.";
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
    <title>Résultat de la modification - Tasty Country</title>
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
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="profil.php" class="nav-active">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
            <button id="bouton" class="btn_theme"><?php echo $texteBouton; ?></button><!-- js -->
        </header>

        <main class="content">
            <fieldset class="profile-section">
                <legend>Statut de la modification</legend>
                <div style="text-align: center; padding: 20px;">
                    <h2 style="color: #00FFFF;"><?php echo $titre_message; ?></h2>
                    <p><?php echo $texte_message; ?></p>
                    <form action="profil.php" style="margin-top: 30px;">
                        <button type="submit" class="btn-edit">Retourner à mon profil</button>
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
