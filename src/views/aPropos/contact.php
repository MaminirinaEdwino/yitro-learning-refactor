<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous - Yitro Learning</title>
    <link rel="icon" href="<?= URL_ROOT ?>asset/images/Yitro consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/contact.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
</head>
<body>
    <!-- Header -->
    <?php require_once './src/components/header.php'?>

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
    <?php require_once './src/components/footer.php'?>

    <!-- JavaScript -->
    <script src="<?= URL_ROOT ?>asset/js/contactAnimation.js">
        
    </script>
</body>
</html>