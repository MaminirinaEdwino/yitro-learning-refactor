<?php

$cours = $params["cours"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Progression - Yitro Learning</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/progressionApprenant.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <?php require_once './src/components/headerapprenant.php'?>

    <section class="progression-section">
        <div class="container">
            <h1>Ma Progression</h1>
            <?php if (empty($cours)): ?>
                <p class="no-courses">Vous n'êtes inscrit à aucun cours pour le moment.</p>
            <?php else: ?>
                <?php foreach ($cours as $c): ?>
                    <?php
                    $progression = $c['total_quiz'] > 0 ? ($c['quiz_reussis'] / $c['total_quiz']) * 100 : 0;
                    ?>
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($c['titre']); ?></h3>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo $progression; ?>%;"></div>
                        </div>
                        <div class="progress-text">
                            <?php echo $c['quiz_reussis']; ?> / <?php echo $c['total_quiz']; ?> quiz réussis (<?php echo round($progression, 1); ?>%)
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="/espace/apprenant" class="btn-back">Retour à l'espace apprenant</a>
        </div>
    </section>

    <script>
        // Animations GSAP
        gsap.from(".progression-section h1", {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".course-card", {
            opacity: 0,
            y: 30,
            duration: 0.8,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".course-card",
                start: "top 80%",
            }
        });

        gsap.from(".progress-bar", {
            width: 0,
            duration: 1.5,
            ease: "power3.inOut",
            stagger: 0.3,
            delay: 0.5
        });

        gsap.from(".btn-back", {
            opacity: 0,
            scale: 0.8,
            duration: 0.5,
            ease: "back.out(1.7)",
            delay: 0.7,
            onComplete: () => {
                gsap.to(".btn-back", {
                    scale: 1.1,
                    duration: 0.2,
                    repeat: -1,
                    yoyo: true,
                    ease: "power1.inOut",
                    paused: true,
                    onStart: function() { this.targets().forEach(btn => btn.addEventListener('mouseenter', () => this.play())) },
                    onComplete: function() { this.targets().forEach(btn => btn.addEventListener('mouseleave', () => this.pause())) }
                });
            }
        });
    </script>
</body>
</html>