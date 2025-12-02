<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisissez votre parcours - Yitro Learning</title>
    <link rel="icon" href="../asset/images/Yitro consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/styles/style.css">
    <link rel="stylesheet" href="../asset/css/styles/inscription.css">
</head>
<body>
    <!-- Header -->
   <?php require_once '../components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Rejoignez Yitro Learning</h1>
            <p>Que vous souhaitiez enseigner ou apprendre, Yitro Learning vous offre une plateforme pour développer vos compétences et partager votre passion.</p>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <!-- Section Choix -->
    <section class="choice-section">
        <h2>Choisissez votre parcours</h2>
        <div class="choice-grid">
            <!-- Carte Formateur -->
            <div class="choice-card fade-in">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Devenir Formateur</h3>
                <p>Partagez votre expertise avec des milliers d'apprenants et inspirez la prochaine génération de professionnels.</p>
                <a href="../page/inscription-formateur.php" class="btn-choice">S'inscrire comme formateur</a>
            </div>
            <!-- Carte Apprenant -->
            <div class="choice-card fade-in">
                <i class="fas fa-user-graduate"></i>
                <h3>Devenir Apprenant</h3>
                <p>Apprenez à votre rythme avec nos cours interactifs en technologie, programmation et bien plus encore.</p>
                <a href="../page/inscription-apprenant.php" class="btn-choice">S'inscrire comme apprenant</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once '../components/footer.php'?>

    <!-- JavaScript -->
    <script>
        // Animation pour le canvas de la section hero
        const canvas = document.getElementById('heros-animation');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = canvas.parentElement.offsetHeight;

        let particlesArray = [];

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 1;
                this.speedX = Math.random() * 1 - 0.5;
                this.speedY = Math.random() * 1 - 0.5;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) {
                    this.speedX = -this.speedX;
                }
                if (this.y < 0 || this.y > canvas.height) {
                    this.speedY = -this.speedY;
                }
            }
            draw() {
                ctx.fillStyle = 'rgba(255,255,255,0.8)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function init() {
            particlesArray = [];
            for (let i = 0; i < 100; i++) {
                particlesArray.push(new Particle());
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
                particlesArray[i].draw();
            }
            requestAnimationFrame(animate);
        }

        init();
        animate();

        window.addEventListener('resize', function() {
            canvas.width = window.innerWidth;
            canvas.height = canvas.parentElement.offsetHeight;
            init();
        });

        // Animation d'apparition au défilement
        const fadeInElements = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        fadeInElements.forEach(element => {
            observer.observe(element);
        });
    </script>
</body>
</html>