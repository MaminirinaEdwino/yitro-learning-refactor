<?php

// Traitement de la création d'un nouveau forum
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre'], $_POST['cours_id'])) {
//     $titre = trim($_POST['titre']);
//     $description = trim($_POST['description'] ?? '');
//     $cours_id = (int)$_POST['cours_id'];
    
//     $stmt = $pdo->prepare("INSERT INTO forum (cours_id, titre, description) VALUES (?, ?, ?)");
//     $stmt->execute([$cours_id, $titre, $description]);
    
//     header("Location: espace_apprenant.php");
//     exit();
// }

// Traitement de la soumission d'un post
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu'], $_POST['forum_id'])) {
//     $contenu = trim($_POST['contenu']);
//     $forum_id = (int)$_POST['forum_id'];
//     $auteur_id = $current_user_id;
    
//     $stmt = $pdo->prepare("INSERT INTO post (auteur_id, forum_id, contenu) VALUES (?, ?, ?)");
//     $stmt->execute([$auteur_id, $forum_id, $contenu]);
    
//     header("Location: forum.php?forum_id=$forum_id&success=Post ajouté avec succès");
//     exit();
// }

// Récupérer les détails du forum
// $forum_id = isset($_GET['forum_id']) ? (int)$_GET['forum_id'] : 0;
$forum_id = $params["forum_id"];
$forum = $params["forum"];
$posts = $params["posts"];

if ($forum_id) {
    // $stmt = $pdo->prepare("SELECT f.*, c.titre AS cours_titre FROM forum f JOIN cours c ON f.cours_id = c.id WHERE f.id = ?");
    // $stmt->execute([$forum_id]);
    // $forum = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // if (!$forum) {
    //     header("Location: espace_apprenant.php");
    //     exit();
    // }
    
    // Récupérer les posts avec le nom et indicateur si c'est l'utilisateur connecté
    // $stmt = $pdo->prepare("
    //     SELECT p.*, 
    //            COALESCE(f.nom_prenom, u.nom) AS auteur_nom,
    //            CASE 
    //                WHEN p.auteur_id = ? THEN 1 
    //                ELSE 0 
    //            END AS is_self,
    //            CASE 
    //                WHEN f.id IS NOT NULL THEN 1 
    //                ELSE 0 
    //            END AS is_formateur
    //     FROM post p 
    //     JOIN utilisateurs u ON p.auteur_id = u.id 
    //     LEFT JOIN formateurs f ON u.email = f.email 
    //     WHERE p.forum_id = ? 
    //     ORDER BY p.date_post ASC
    // ");
    // $stmt->execute([$current_user_id, $forum_id]);
    // $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Yitro Learning</title>
    <link rel="stylesheet" href="../../asset/css/styles.css">
    <link rel="icon" href="asset/images/Yitro_consulting.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/apprenantForum.css">
</head>
<body>
   <?php require_once './src/components/headerapprenant.php'?>

    <div class="main--content">
        <div class="forum-section">
            <?php if ($forum_id && $forum): ?>
                <h3>Forum</h3>
                <div class="forum-card">
                    <h4><?php echo htmlspecialchars($forum['titre']); ?></h4>
                    <p>Dans le cours : <?php echo htmlspecialchars($forum['cours_titre']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($forum['description'])); ?></p>
                    <p class="date">Créé le : <?php echo date('d/m/Y H:i', strtotime($forum['date_creation'])); ?></p>
                    <div class="post-list">
                        <?php if (empty($posts)): ?>
                            <div class="no-posts">Aucun message dans ce forum pour le moment.</div>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <div class="post-item <?php echo $post['is_self'] ? 'post-formateur' : 'post-apprenant'; ?>">
                                    <div class="author">
                                        <div class="avatar">
                                            <i class="fas <?php echo $post['is_self'] ? 'fa-user-circle' : ($post['is_formateur'] ? 'fa-user' : 'fa-user-graduate'); ?>"></i>
                                        </div>
                                        <?php echo htmlspecialchars($post['auteur_nom'] ?? 'Inconnu'); ?>
                                    </div>
                                    <div class="date"><?php echo date('d/m/Y H:i', strtotime($post['date_post'])); ?></div>
                                    <div class="content"><?php echo nl2br(htmlspecialchars($post['contenu'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <form action="/post/new" method="POST">
                            <input type="hidden" name="forum_id" value="<?php echo $forum_id; ?>">
                            <label for="contenu">Répondre au forum</label>
                            <textarea name="contenu" id="contenu" class="form-control" rows="4" required placeholder="Votre réponse..."></textarea>
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Publier</button>
                        </form>
                    </div>
                </div>
                <?php if (isset($_GET['success'])): ?>
                    <div class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-posts">Sélectionnez un forum depuis la page d'accueil.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once './src/components/footer.php'?>

    <script src="<?= URL_ROOT ?>asset/apprenantForum.js">
        // Animations GSAP
        gsap.from(".forum-card", { opacity: 0, y: 40, duration: 1, ease: "power3.out", delay: 0.3 });
        gsap.from(".forum-section h3", { opacity: 0, y: 20, duration: 0.8, ease: "power2.out", delay: 0.2 });
        gsap.from(".post-apprenant", { opacity: 0, x: -20, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.5 });
        gsap.from(".post-formateur", { opacity: 0, x: 20, duration: 0.6, stagger: 0.1, ease: "power2.out", delay: 0.5 });
        gsap.from(".form-group", { opacity: 0, y: 30, duration: 0.8, ease: "power2.out", delay: 0.7 });
        gsap.from(".btn-success", { opacity: 0, scale: 0.7, duration: 0.6, ease: "back.out(2)", delay: 0.9 });
        gsap.from(".success, .no-posts", { opacity: 0, x: -30, duration: 1, ease: "power3.out", delay: 0.3 });

        // Animation bouton survol et clic
        document.querySelectorAll(".btn").forEach(btn => {
            btn.addEventListener("mouseenter", () => {
                gsap.to(btn, { scale: 1.05, boxShadow: "0 6px 12px rgba(0,0,0,0.2)", duration: 0.3 });
            });
            btn.addEventListener("mouseleave", () => {
                gsap.to(btn, { scale: 1, boxShadow: "0 4px 10px rgba(0,0,0,0.15)", duration: 0.3 });
            });
            btn.addEventListener("click", () => {
                gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 });
            });
        });

        // Faire défiler post-list vers le bas
        document.querySelectorAll(".post-list").forEach(postList => {
            postList.scrollTop = postList.scrollHeight;
        });

        // Défiler vers le bas après ajout d'un post (si succès)
        <?php if (isset($_GET['success'])): ?>
            document.querySelectorAll(".post-list").forEach(postList => {
                gsap.to(postList, {
                    scrollTo: { y: "max" },
                    duration: 0.5,
                    ease: "power2.out"
                });
            });
        <?php endif; ?>
    </script>
</body>
</html>

