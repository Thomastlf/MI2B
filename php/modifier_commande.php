<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

$id_commande = $_GET['id'] ?? null;
$commandes = json_decode(file_get_contents("../json/commande.json"), true);
$menu = json_decode(file_get_contents("../json/menu.json"), true);

$ma_commande = null;
foreach ($commandes as $c) {
    if ($c['id'] == $id_commande && $c['client'] == $_SESSION['email'] && $c['statut'] == 'a_preparer') {
        $ma_commande = $c;
        break;
    }
}

if (!$ma_commande) {
    die("<h2 style='color:white; text-align:center; padding: 50px;'>Commande introuvable ou déjà en préparation.</h2>");
}

$quantites_actuelles = [];
foreach ($ma_commande['articles'] as $art) {
    $quantites_actuelles[$art['nom']] = $art['quantite'];
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
    <title>Modifier la Commande - Tasty Country</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perso.css">
    <link id="css" rel="stylesheet" href=<?php echo $css; ?>><!-- js -->
    <script src="../js/modifier_commande.js" defer></script>
    <script src="../js/theme.js" defer></script><!-- js / defer pour n'exécuter le script js qu'une fois que le navigateur aura chargé le html dans le dom -->
</head>
<body>
    <div class="site-container">
        <main class="content">
            <fieldset class="profile-section">
                <legend>Ajustez votre vol #<?php echo $ma_commande['id']; ?></legend>
                
                <form action="traitement_modif.php" method="POST">
                    <input type="hidden" name="id_commande" value="<?php echo $ma_commande['id']; ?>">
                    <input type="hidden" id="total-initial" value="<?php echo $ma_commande['total']; ?>">
                    <input type="hidden" id="montant-supplementaire" name="montant_supplementaire" value="0">
                    
                    <div style="margin-bottom: 20px;">
                        <p>Total payé initialement : <strong><?php echo number_format($ma_commande['total'], 2); ?> €</strong></p>
                        <p>Nouveau total : <strong id="nouveau-total-display"><?php echo number_format($ma_commande['total'], 2); ?> €</strong></p>
                        <p id="message-diff" style="margin-top: 10px;"></p>
                    </div>

                    <?php foreach ($menu as $index => $plat): 
                        $qte = $quantites_actuelles[$plat['nom']] ?? 0;
                        $inputId = "qte_" . $index;
                    ?>
                        <div class="info-row" style="align-items: center;">
                            <div class="label" style="width: 50%;"><?php echo $plat['nom']; ?> (<?php echo $plat['prix']; ?>€)</div>
                            <div class="value" style="display:flex; gap:10px; align-items:center;">
                                <button class="btn-moins btn-edit" data-target="<?php echo $inputId; ?>" style="width:40px; margin:0;">-</button>
                                <input type="number" id="<?php echo $inputId; ?>" name="qte[<?php echo $plat['nom']; ?>]" value="<?php echo $qte; ?>" data-prix="<?php echo $plat['prix']; ?>" class="qte-input" style="width: 50px; text-align: center; background: #1D3557; color: white; border: 1px solid #00FFFF;" readonly>
                                <button class="btn-plus btn-edit" data-target="<?php echo $inputId; ?>" style="width:40px; margin:0;">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" id="btn-valider-modif" class="btn-edit" style="width: 100%; margin-top: 20px; font-size: 1.1rem;">
                        Calcul en cours...
                    </button>
                </form>
            </fieldset>
        </main>
    </div>
</body>
</html>
