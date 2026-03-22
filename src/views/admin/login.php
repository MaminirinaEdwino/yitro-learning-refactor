<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Connexion Admin</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
     <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/adminLoginStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion Administrateur</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']); 
        }
        ?>
        <form method="POST" action="/admin/login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>