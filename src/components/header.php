
<?php
require_once dirname(__DIR__).'/utils/relativeroute.php';
?>
<header>
        <nav class="main-nav">
            <div class="container">
                <div class="logo">
                    <img src="<?= URL_ROOT ?>asset/images/logo.png" alt="Yitro E-Learning">
                    <a href="/" class="logo-text">SK Yitro Learning</a>
                </div>
                <ul class="nav-list">
                    <li class="dropdown">
                        <a href="#">À propos <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="/apropos/decouvrir-yitro">Découvrir Yitro</a></li>
                            <li><a href="/apropos/faq">FAQ</a></li>
                            <li><a href="/apropos/contact">Contact</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="<?php echo To_relative_path('page/catalogue.php')?>">Nos formations <i class="fas fa-chevron-down"></i></a>
                        
                    </li>
                </ul>
                <div class="auth-links">
                    <ul class="nav-list">
                        <li><a href="<?php echo To_relative_path('authentification/connexion.php')?>" class="btn-primary">Connexion</a></li>
                        <li><a href="<?php echo To_relative_path('authentification/inscription.php')?>" class="btn-primary">Inscription</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>