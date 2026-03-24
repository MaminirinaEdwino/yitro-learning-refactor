<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning</title>
    <link rel="icon" href="../asset/images/Yitro_consulting.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/formulaire-formateur.css">
    <style>
        /* Styles pour la section de bienvenue */
        .btn.btn-primary {
            padding: 14px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            align-self: center;
            margin-top: 20px;
            text-decoration: none;
        }

        .step {
            height: 5px;
            width: 100%;
            margin: 1px;
            border-radius: 5px;
        }

        .steped {
            background-color: #0164ce;
        }

        #step {
            display: flex;
        }

        .welcome-trainer {
            position: relative;
            background: linear-gradient(109deg, #01ae8f, #132F3F);
            padding: 100px 20px 60px;
            text-align: center;
            overflow: hidden;
        }

        .welcome-trainer-content {
            max-width: 800px;
            margin: 0 auto;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .welcome-trainer-content h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
            animation: fadeInUp 1s ease-out;
        }

        .welcome-trainer-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 1s ease-out 0.2s;
            animation-fill-mode: both;
        }

        .welcome-trainer-content .cta-buttons {
            display: inline-block;
            padding: 14px 28px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            background-color: #ff6f61;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            animation: fadeInUp 1s ease-out 0.4s;
            animation-fill-mode: both;
        }

        .welcome-trainer-content .cta-buttons:hover {
            background-color: #e55a50;
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #welcome-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.2;
        }

        /* Animation pour les éléments */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .welcome-trainer {
                padding: 80px 15px 40px;
            }

            .welcome-trainer-content h1 {
                font-size: 2rem;
            }

            .welcome-trainer-content p {
                font-size: 1rem;
            }

            .welcome-trainer-content .cta-buttons {
                padding: 12px 24px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php require_once './src/components/header.php' ?>

    <!-- Section de bienvenue -->
    <section class="welcome-trainer">
        <div class="welcome-trainer-content">
            <h1>Devenez formateur chez Yitro Learning</h1>
            <p>Partagez votre passion et votre expertise avec une communauté mondiale d'apprenants. Rejoignez notre plateforme pour créer des formations impactantes et inspirer des milliers de personnes. Remplissez le formulaire ci-dessous pour commencer votre aventure !</p>
            <a href="#formulaireYitro" class="cta-buttons">Proposer votre formation</a>
        </div>
        <canvas id="welcome-animation"></canvas>
    </section>

    <div class="yitro-form-wrapper">
        <div id="step"></div>
        <form id="formulaireYitro" action="/signin/postuler" method="POST" enctype="multipart/form-data">
            <!-- 1. Informations personnelles -->
            <div class="yitro-section" id="section1">
                <h2>1. Informations personnelles</h2>

                <div class="yitro-input-group">
                    <label class="yitro-label">Nom et prénom *</label>
                    <input type="text" class="yitro-input" name="nom_prenom" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Adresse e-mail *</label>
                    <input type="email" class="yitro-input" name="email" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Numéro de téléphone *</label>
                    <input type="tel" class="yitro-input" name="telephone" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Ville et pays de résidence *</label>
                    <input type="text" class="yitro-input" name="ville_pays" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Lien LinkedIn ou site pro (optionnel)</label>
                    <input type="url" class="yitro-input" name="linkedin">
                </div>
            </div>

            <!-- 2. Profil professionnel -->
            <div class="yitro-section" id="section2">
                <h2>2. Profil professionnel</h2>

                <div class="yitro-input-group">
                    <label class="yitro-label">Intitulé de votre activité / métier *</label>
                    <input type="text" class="yitro-input" name="intitule_metier" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Expérience dans la formation *</label>
                    <select class="yitro-select" name="experience_formation" required>
                        <option>Aucune expérience (débutant motivé·e)</option>
                        <option>1 à 2 ans</option>
                        <option>3 à 5 ans</option>
                        <option>+5 ans</option>
                    </select>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Détaillez vos expériences :</label>
                    <textarea class="yitro-textarea" rows="3" name="detail_experience"></textarea>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Votre CV (PDF, Word, max 5 Mo) *</label>
                    <input type="file" class="yitro-input" accept=".pdf,.doc,.docx" name="cv" required>
                </div>
            </div>

            <!-- 3. Domaines d'expertise -->
            <div class="yitro-section" id="section3">
                <h2>3. Domaines d’expertise</h2>

                <div class="yitro-checkbox-group">
                    <label><input type="checkbox" name="categories[]"> Technologie & Programmation</label><br>
                    <label><input type="checkbox" name="categories[]"> Business & Entrepreneuriat</label><br>
                    <label><input type="checkbox" name="categories[]"> Marketing Digital</label><br>
                    <label><input type="checkbox" name="categories[]"> Compétences Bureautiques & Outils</label><br>
                    <label><input type="checkbox" name="categories[]"> Finance & Investissement</label><br>
                    <label><input type="checkbox" name="categories[]"> Santé & Bien-être</label><br>
                    <label><input type="checkbox" name="categories[]"> Développement Personnel</label><br>
                    <label><input type="checkbox" name="categories[]"> Arts, Design & Artisanat</label><br>
                    <label><input type="checkbox" name="categories[]"> Langues Étrangères</label>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Autre domaine (précisez) :</label>
                    <input type="text" class="yitro-input" name="autre_domaine">
                </div>
            </div>

            <!-- 4. Proposition de contenu -->
            <div class="yitro-section" id="section4">
                <h2>4. Proposition de contenu</h2>

                <div class="yitro-input-group">
                    <label class="yitro-label">Titre du cours *</label>
                    <input type="text" class="yitro-input" name="titre_cours" required>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Objectif pédagogique *</label>
                    <textarea class="yitro-textarea" name="objectif" required></textarea>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Public cible *</label>
                    <select class="yitro-select" name="public_cible">
                        <option>Débutants</option>
                        <option>Public en reconversion</option>
                        <option>Professionnels intermédiaires</option>
                        <option>Avancé / expert</option>
                    </select>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Détail complémentaire :</label>
                    <input type="text" class="yitro-input" name="detail_complementaire">
                </div>

                <div class="yitro-checkbox-group">
                    <p><strong>Formats envisagés *</strong></p>
                    <label><input type="checkbox" name="formats[]"> Vidéo</label><br>
                    <label><input type="checkbox" name="formats[]"> Quiz</label><br>
                    <label><input type="checkbox" name="formats[]"> Fiches pratiques / PDF</label><br>
                    <label><input type="checkbox" name="formats[]"> Podcast / audio</label><br>
                    <label><input type="checkbox" name="formats[]"> Cas pratique / exercice</label><br>
                    <label>Autre : <input type="text" class="yitro-input" style="margin-top: 8px;" name="format_autre"></label>
                </div>

                <div class="yitro-input-group">
                    <label class="yitro-label">Durée estimée *</label>
                    <select class="yitro-select" name="duree_estimee" required>
                        <option>
                            < 1 heure</option>
                        <option>1 à 2 heures</option>
                        <option>2 à 4 heures</option>
                        <option>Plus de 4 heures</option>
                    </select>
                </div>

                <div class="yitro-radio-group">
                    <p><strong>Souhaitez-vous que votre formation soit :</strong></p>
                    <label><input type="radio" name="type_formation" required> Gratuite</label><br>
                    <label><input type="radio" name="type_formation" required> Payante</label><br>
                    <label><input type="radio" name="type_formation" required> Les deux</label>
                </div>
            </div>

            <!-- 5. Motivation -->
            <div class="yitro-section" id="section5">
                <h2>5. Motivation et engagement</h2>

                <div class="yitro-input-group">
                    <label class="yitro-label">Pourquoi rejoindre Yitro Learning ? *</label>
                    <textarea class="yitro-textarea" name="motivation" required></textarea>
                </div>

                <div class="yitro-checkbox-group">
                    <p><strong>Valeurs qui vous motivent *</strong></p>
                    <label><input type="checkbox" name="valeurs[]"> Transmission du savoir</label><br>
                    <label><input type="checkbox" name="valeurs[]"> Inclusion & diversité</label><br>
                    <label><input type="checkbox" name="valeurs[]"> Innovation pédagogique</label><br>
                    <label><input type="checkbox" name="valeurs[]"> Entraide</label><br>
                    <label><input type="checkbox" name="valeurs[]"> Impact social</label>
                </div>

                <div class="yitro-radio-group">
                    <p><strong>Profil public sur la plateforme ? *</strong></p>
                    <label><input type="radio" name="profil_public" required> Oui</label><br>
                    <label><input type="radio" name="profil_public" required> Non</label><br>
                    <label><input type="radio" name="profil_public" required> À discuter</label>
                </div>
            </div>

            <!-- 6. Finalisation -->
            <div class="yitro-section" id="section6">
                <h2>6. Finalisation</h2>

                <div class="yitro-checkbox-group">
                    <label><input type="checkbox" required>
                        Je confirme avoir lu et accepté la <a href="lien-charte.pdf" target="_blank">charte qualité formateur</a>.
                    </label>
                </div>

                <div class="yitro-checkbox-group">
                    <label><input type="checkbox" required>
                        Je consens à la gestion de mes données selon la <a href="lien-rgpd.pdf" target="_blank">politique RGPD</a>.
                    </label>
                </div>

                <button type="submit" class="yitro-submit-btn">Soumettre ma candidature</button>
            </div>
        </form>
        <div style="display: flex; ">
            <div></div>
            <div id="step-btn"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once './src/components/footer.php' ?>
    <script src="<?= URL_ROOT ?>asset/js/formStepHandler.js"></script>
    <script>
        // Animation de particules pour la section de bienvenue
        const canvas = document.getElementById('welcome-animation');
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
    </script>
    <script src="../asset/js/formulaire-formateur.js"></script>
</body>

</html>