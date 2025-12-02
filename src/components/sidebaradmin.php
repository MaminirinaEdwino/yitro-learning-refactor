
<?php require_once dirname(__DIR__).'/utils/relativeroute.php';?>
<div class="sidebar">
        <div class="logo">
            <img src="<?php echo To_relative_path('asset/images/logo.png')?>" alt="Yitro E-Learning" style=" height: 50px;border-radius:5px;background: wheat;">
        </div>
        <ul class="menu" style="margin-top:-14px">
            <li class="active">
                <a href="<?php echo To_relative_path('Espace/admin/backoffice.php')?>"><i class="fas fa-tachometer-alt"></i><span>Tableau de bord</span></a>
            </li>
            <li>
                <a href="<?php echo To_relative_path('Espace/admin/gestion_utilisateurs/gestion_utilisateur.php')?>"><i class="fas fa-user-cog"></i><span>Gestion utilisateur</span></a>
            </li>
            <li>
                <a href="<?php echo To_relative_path('Espace/admin/gestion_formations/gestion_formations.php')?>"><i class="fas fa-chart-line"></i><span>Gestion formations</span></a>
            </li>
            <li>
                <a href="<?php echo To_relative_path('Espace/admin/gestion_forum.php')?>"><i class="fas fa-comments"></i><span>Forum</span></a>
            </li>
            <li>
                <a href="<?php echo To_relative_path('Espace/admin/progression_apprenant.php')?>"><i class="fas fa-chart-line"></i><span>Progression Apprenant</span></a>
            </li>
            <li>
                <a href="<?php echo To_relative_path('Espace/admin/espace-certificat.php')?>"><i class="fas fa-certificate"></i><span>Certificat Apprenant</span></a>
            </li>
            <li class="logout">
                <a href="<?php echo To_relative_path('authentification/logout.php')?>"><i class="fas fa-sign-out-alt"></i><span>Déconnexion</span></a>
            </li>
        </ul>
    </div>