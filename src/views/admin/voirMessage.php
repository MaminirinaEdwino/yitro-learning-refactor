<?php
$success_message = '';
$error_message = '';
$posts = $params["posts"];
$forum = $params["forums"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Messages du Forum</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/voirMessage.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
</head>

<body>
    <?php require_once "./src/components/sidebaradmin.php" ?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Messages du Forum</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <img src="<?= URL_ROOT ?>asset/images/lito.jpg" alt="User Profile">
            </div>
        </div>

        <div class="forum-section">
            <?php if ($success_message): ?>
                <div class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div class="forum-card">
                <a href="gestion_forum.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour aux forums</a>
                <h4><?php echo htmlspecialchars($forum['titre']); ?></h4>
                <p>Cours : <?php echo htmlspecialchars($forum['cours_titre']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($forum['description'])); ?></p>
                <p class="date">Créé le : <?php echo date('d/m/Y H:i', strtotime($forum['date_creation'])); ?></p>
                <div class="post-list">
                    <?php if (empty($posts)): ?>
                        <div class="no-posts">Aucun message dans ce forum pour le moment.</div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post-item <?php echo $post['is_formateur'] ? 'post-formateur' : 'post-apprenant'; ?>">
                                <div class="author">
                                    <div class="avatar">
                                        <i class="fas <?php echo $post['is_formateur'] ? 'fa-user' : 'fa-user-graduate'; ?>"></i>
                                    </div>
                                    <?php echo htmlspecialchars($post['auteur_nom'] ?? 'Inconnu'); ?>
                                </div>
                                <div class="date"><?php echo date('d/m/Y H:i', strtotime($post['date_post'])); ?></div>
                                <div class="content"><?php echo nl2br(htmlspecialchars($post['contenu'])); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animations GSAP
        gsap.from(".header--wrapper", {
            opacity: 0,
            y: -20,
            duration: 0.8,
            ease: "power3.out"
        });
        gsap.from(".forum-card", {
            opacity: 0,
            y: 40,
            duration: 1,
            ease: "power3.out",
            delay: 0.2
        });
        gsap.from(".post-apprenant", {
            opacity: 0,
            x: -20,
            duration: 0.6,
            stagger: 0.1,
            ease: "power2.out",
            delay: 0.4
        });
        gsap.from(".post-formateur", {
            opacity: 0,
            x: 20,
            duration: 0.6,
            stagger: 0.1,
            ease: "power2.out",
            delay: 0.4
        });
        gsap.from(".success, .error", {
            opacity: 0,
            x: -30,
            duration: 1,
            ease: "power3.out",
            delay: 0.3
        });
        gsap.from(".no-posts", {
            opacity: 0,
            x: -30,
            duration: 1,
            ease: "power3.out",
            delay: 0.3
        });

        // Animation bouton survol et clic
        document.querySelectorAll(".btn-back").forEach(btn => {
            btn.addEventListener("mouseenter", () => {
                gsap.to(btn, {
                    scale: 1.05,
                    boxShadow: "0 6px 12px rgba(0,0,0,0.2)",
                    duration: 0.3
                });
            });
            btn.addEventListener("mouseleave", () => {
                gsap.to(btn, {
                    scale: 1,
                    boxShadow: "0 4px 10px rgba(0,0,0,0.15)",
                    duration: 0.3
                });
            });
            btn.addEventListener("click", () => {
                gsap.to(btn, {
                    scale: 0.95,
                    duration: 0.1,
                    yoyo: true,
                    repeat: 1
                });
            });
        });

        // Faire défiler post-list vers le bas
        document.querySelectorAll(".post-list").forEach(postList => {
            postList.scrollTop = postList.scrollHeight;
        });

        // Défiler vers le bas après chargement (si succès ou erreur)
        <?php if ($success_message || $error_message): ?>
            document.querySelectorAll(".post-list").forEach(postList => {
                gsap.to(postList, {
                    scrollTo: {
                        y: "max"
                    },
                    duration: 0.5,
                    ease: "power2.out"
                });
            });
        <?php endif; ?>
    </script>
</body>

</html>