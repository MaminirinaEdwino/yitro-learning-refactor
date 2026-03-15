<header>
    <nav class="main-nav">
        <div class="logo">
            <img src="<?= URL_ROOT ?>asset/images/logo.png" alt="SK Yitro E-Learning">
            <a href="/espace/apprenant" class="logo-text">SK Yitro Learning</a>
        </div>
        <ul class="nav-list">
            <li><a href="/formation/catalogue">Catalogues</a></li>
            <li><a href="/espace/apprenant/progression">Ma progression</a></li>
            <li><a href="mes_cours.php">Mes Cours</a></li>

        </ul>
        <div class="auth-links">
            <ul class="nav-list">
                <li>
                    <a href="#" class="btn-primary">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['user_nom']); ?>
                    </a>
                </li>
                <li><a href="/logout" class="btn-primary">Déconnexion</a></li>
            </ul>
        </div>
    </nav>
</header>