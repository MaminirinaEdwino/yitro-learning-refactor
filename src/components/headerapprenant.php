
<?php require_once dirname(__DIR__).'/utils/relativeroute.php'?>
<header>
        <nav class="main-nav">
            <div class="logo">
                <img src="<?php echo To_relative_path('asset/images/logo.png')?>"  alt="SK Yitro E-Learning">
                <a href="<?php echo To_relative_path('Espace/apprenant/espace_apprenant.php')?>" class="logo-text">SK Yitro Learning</a>
            </div>
            <ul class="nav-list">
                <li><a href="<?php echo To_relative_path('Espace/apprenant/espace_apprenant.php')?>">Catalogues</a></li>
                <li><a href="progression_apprenant.php">Ma progression</a></li>
                <li><a href="mes_cours.php">Mes Cours</a></li>

            </ul>
            <div class="auth-links">
                <ul class="nav-list">
                    <li>
                        <a href="#" class="btn-primary">
                            <i class="fas fa-user-circle"></i>
                            <?php echo htmlspecialchars($user['nom']); ?>
                        </a>
                    </li>
                    <li><a href="../../authentification/logout.php" class="btn-primary">Déconnexion</a></li>
                </ul>
            </div>
        </nav>
    </header>