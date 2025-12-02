<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Yitro Learning</title>
    <link rel="icon" href="../asset/images/Yitro_consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/styles/style.css">
    <link rel="stylesheet" href="../asset/css/styles/connexion_page.css">
</head>
<body>
    <!-- Header -->
    <?php require_once '../components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Connectez-vous à Yitro Learning</h1>
            <p>Accédez à votre espace formateur ou apprenant pour commencer ou continuer votre parcours d'apprentissage.</p>
            <a href="#connexion" class="cta-buttons">Se connecter maintenant</a>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <!-- Section Connexion -->
    <section class="connexion-section" id="connexion">
        <!-- Formulaire Formateur -->
        <div class="connexion-card fade-in">
            <div class="connexion-form">
                <h2>Connexion Formateur</h2>
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success']) . '</p>';
                    unset($_SESSION['success']);
                }
                ?>
                <form id="formateurForm" action="../authentification/connexion-formateur.php" method="POST">
                    <div class="form-group">
                        <label for="emailFormateur" class="form-label">Adresse e-mail *</label>
                        <input type="email" class="form-control" id="emailFormateur" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordFormateur" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control" id="passwordFormateur" name="password" minlength="8" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>
            </div>
            <div class="connexion-guide">
                <h3>Guide pour les Formateurs</h3>
                <p>Connectez-vous pour gérer vos cours, interagir avec vos apprenants et suivre leurs progrès.</p>
                <ul>
                    <li>Utilisez l'e-mail fourni lors de votre candidature.</li>
                    <li>Problèmes de connexion ? Contactez <a href="mailto:formateur@yitro-consulting.com">formateur@yitro-consulting.com</a>.</li>
                    <li>Envie de rejoindre notre équipe ? <a href="formulaire-formateur.php">Postulez ici</a>.</li>
                </ul>
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

        // Mise en surbrillance des champs actifs et validation
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('active');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('active');
            });
            input.addEventListener('input', () => {
                if (input.validity.valid) {
                    input.classList.add('valid');
                    input.classList.remove('invalid');
                } else {
                    input.classList.add('invalid');
                    input.classList.remove('valid');
                }
            });
        });

        // Animation du bouton de soumission
        const submitButtons = document.querySelectorAll('.btn-primary');
        submitButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const form = btn.closest('form');
                if (!form.checkValidity()) {
                    e.preventDefault();
                    btn.classList.add('shake');
                    setTimeout(() => btn.classList.remove('shake'), 500);
                }
            });
        });
    </script>
</body>
</html>