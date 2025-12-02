<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Yitro Learning</title>
    <link rel="icon" href="../asset/images/Yitro consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../asset/css/contact.css">
    <link rel="stylesheet" href="../asset/css/styles/style.css">
</head>
<body>
    <!-- Header -->
    <?php require_once '../components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Contactez-nous</h1>
            <p>Vous avez une question ou besoin d'assistance ? Envoyez-nous un message ou utilisez nos coordonnées ci-dessous.</p>
            <a href="#contact" class="cta-buttons">Envoyer un message</a>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <!-- Affichage des messages stylés -->
    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        <div class="message <?php echo isset($_SESSION['success']) ? 'success' : 'error'; ?>">
            <?php 
            if (isset($_SESSION['success'])) {
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['error'])) {
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Section Contact -->
    <section class="contact-section" id="contact">
        <div class="contact-card fade-in">
            <div class="contact-form">
                <h2>Envoyez-nous un message</h2>
                <form id="contactForm" action="submit-contact.php" method="POST">
                    <div class="form-group">
                        <label for="nom" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse e-mail *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="sujet" class="form-label">Sujet *</label>
                        <input type="text" class="form-control" id="sujet" name="sujet" required>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Envoyer</button>
                </form>
            </div>
            <div class="contact-info">
                <h2>Nos coordonnées</h2>
                <p>Contactez-nous directement ou visitez-nous à notre adresse.</p>
                <ul>
                    <li><strong>Adresse :</strong> Lot 304-D_240 Andafiatsimo Ambohitrinibe, 110 Antsirabe</li>
                    <li><strong>Téléphone :</strong> +261 34 53 313 87</li>
                    <li><strong>Email :</strong> <a href="mailto:contact@yitro-consulting.com">contact@yitro-consulting.com</a></li>
                    <li><strong>Horaires :</strong> Lun – Ven | 08h – 12h | 14h – 18h</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once '../components/footer.php'?>

    <!-- JavaScript -->
    <script>
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