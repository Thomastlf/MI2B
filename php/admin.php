<?php
session_start();

if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: accueil.php");
    exit();
}

$json_path = '../json/utilisateur.json'; 
$users = json_decode(file_get_contents($json_path), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_target = $_POST['user_email'];
    foreach ($users as &$u) {
        if ($u['email'] === $email_target) {

            if (isset($_POST['action']) && $_POST['action'] === 'toggle_block') {
                $u['statut'] = ($u['statut'] === 'Bloqué') ? 'Actif' : 'Bloqué';
            }

            if (isset($_POST['new_role'])) {
                $u['role'] = $_POST['new_role'];
            }

            if (isset($_POST['new_level'])) {
                $u['niveau'] = $_POST['new_level'];
            }

            if (isset($_POST['new_discount'])) {
                $u['remise'] = $_POST['new_discount'];
            }
            break;
        }
    }
    file_put_contents($json_path, json_encode($users, JSON_PRETTY_PRINT));
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="icon" type="image/png" href="../img/Logo_Tasty_Country.png">
    <title>Terminal Admin - Tasty Country</title>
</head>
<body>
    <div class="site-container">
        <header class="header">
            <div class="header-content">
                <div class="brand">
                    <h1>Tasty Country ✈️</h1>
                    <span class="badge-pro">TOUR DE CONTRÔLE</span>
                </div>
                <nav class="main-nav">
                    <ol>
                        <li><a href="accueil.php">Accueil</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="commande.php">Commandes</a></li>
                        <li><a href="admin.php" class="nav-active">Gestion admin</a></li>
                        <li><a href="profil.php">Mon Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>
                    </ol>
                </nav>
            </div>
        </header>

        <main class="content">
            <h2 class="page-title">Manifeste des Passagers 📋</h2>

            <section class="admin-controls">
                <div class="filter-group">
                    <label style="color: white; margin-right: 10px;">Filtrer :</label>
                    <select id="user-filter" class="role-filter-select">
                        <option value="all">Tous les profils</option>
                        <option value="Client">Clients</option>
                        <option value="Restaurateur">Restaurateurs</option>
                        <option value="Livreur">Livreurs</option>
                        <option value="Admin">Administrateurs</option>
                    </select>
                </div>
                <input type="text" placeholder="Rechercher un passager..." class="search-bar">
            </section>

            <div class="table-container">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Niveau</th>
                            <th>Remise</th>
                            <th>Nom & Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="text-align: center;">
                                <form method="POST">
                                    <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
                                    <input type="hidden" name="action" value="toggle_block">
                                    <button type="submit" style="background:none; border:none; cursor:pointer; font-size:1.4rem;">
                                        <?php echo ($user['statut'] === 'Bloqué') ? '🚫' : '✅'; ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
                                    <select name="new_level" onchange="this.form.submit()" class="role-selector">
                                        <option value="Classique" <?php if(($user['niveau'] ?? '') == 'Classique') echo 'selected'; ?>>Classique</option>
                                        <option value="Premium" <?php if(($user['niveau'] ?? '') == 'Premium') echo 'selected'; ?>>Premium 🥈</option>
                                        <option value="VIP" <?php if(($user['niveau'] ?? '') == 'VIP') echo 'selected'; ?>>VIP 🥇</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
                                    <select name="new_discount" onchange="this.form.submit()" class="role-selector" style="width: 80px;">
                                        <?php for ($i = 0; $i <= 5; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php if(($user['remise'] ?? 0) == $i) echo 'selected'; ?>>
                                                Niv. <?php echo $i; ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                            </td>
                            <td><?php echo htmlspecialchars($user['nom'] . " " . $user['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
                                    <select name="new_role" onchange="this.form.submit()" class="role-selector">
                                        <option value="Client" <?php if(strtolower($user['role']) == 'client') echo 'selected'; ?>>Client</option>
                                        <option value="Restaurateur" <?php if(strtolower($user['role']) == 'restaurateur') echo 'selected'; ?>>Restaurateur</option>
                                        <option value="Livreur" <?php if(strtolower($user['role']) == 'livreur') echo 'selected'; ?>>Livreur</option>
                                        <option value="Admin" <?php if(strtolower($user['role']) == 'admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td><a href="profil.php?email=<?php echo $user['email']; ?>" class="btn-view">Voir</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <footer class="footer">
            <div class="footer-bottom">
                <p>&copy; 2026 Tasty Country - Terminal Administrateur CyTech</p>
                <a href="#top" style="color: #00FFFF; text-decoration: none; font-weight: bold; display: block; margin-top: 10px;">Revenir en haut ✈️</a>
            </div>
        </footer>
    </div>
</body>
</html>
