

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning</title>
    <link rel="stylesheet" href="../asset/css/styles/style.css">
    <link rel="icon" href="../asset/images/Yitro_consulting.png" type="image/png">
    <link rel="stylesheet" href="../asset/css/inscription-apprenant.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php require_once './src/components/header.php'?>

    <!-- Section Hero -->
    <section class="heros">
        <div class="heros-content">
            <h1>Rejoignez Yitro Learning et commencez à apprendre autrement : à votre rythme, sur mobile, en toute liberté.</h1>
            <p>Apprenez, progressez, obtenez des badges et des certificats.</p>
            <a href="#registrationForm" class="cta-buttons">Inscrivez-vous maintenant</a>
        </div>
        <canvas id="heros-animation"></canvas>
    </section>

    <div class="container my-5">
        <form id="registrationForm" action="inscription-apprenant.php" method="POST" enctype="multipart/form-data">
            <!-- Informations de base -->
            <div class="form-section">
                <h4>1. Informations de base</h4>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom et prénom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe *</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Numéro de téléphone (WhatsApp)</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone">
                </div>
            </div>

            <!-- Localisation & langue -->
            <div class="form-section">
                <h4>2. Localisation & langue</h4>
                <div class="mb-3">
                    <label for="pays" class="form-label">Pays de résidence *</label>
                    <select id="pays" class="form-select" name="pays" required>
                        <option value="">-- Sélectionner un pays --</option>
                        <option>Madagascar</option>
                        <option>France</option>
                        <option>Cameroun</option>
                        <option>Canada</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="langue" class="form-label">Langue préférée *</label>
                    <select id="langue" class="form-select" name="langue" required>
                        <option value="">-- Sélectionner --</option>
                        <option>Français</option>
                        <option>Anglais</option>
                        <option>Autre</option>
                    </select>
                    <input type="text" class="form-control mt-2" id="autreLangue" name="autre_langue" placeholder="Précisez si autre...">
                </div>
            </div>

            <!-- Objectif d'apprentissage -->
            <div class="form-section">
                <h4>3. Objectif d'apprentissage</h4>
                <label class="form-label">Pourquoi vous inscrivez-vous ? *</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="emploi" name="objectifs[]" value="Trouver un emploi ou me reconvertir">
                    <label class="form-check-label" for="emploi">Trouver un emploi ou me reconvertir</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="competencesPro" name="objectifs[]" value="Améliorer mes compétences pro">
                    <label class="form-check-label" for="competencesPro">Améliorer mes compétences pro</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="entreprise" name="objectifs[]" value="Créer mon activité / entreprise">
                    <label class="form-check-label" for="entreprise">Créer mon activité / entreprise</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="apprendre" name="objectifs[]" value="Apprendre pour moi-même">
                    <label class="form-check-label" for="apprendre">Apprendre pour moi-même</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="softSkills" name="objectifs[]" value="Développer mes soft skills">
                    <label class="form-check-label" for="softSkills">Développer mes soft skills</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="stress" name="objectifs[]" value="Mieux gérer mon temps / stress / vie perso">
                    <label class="form-check-label" for="stress">Mieux gérer mon temps / stress / vie perso</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="transition" name="objectifs[]" value="Agir pour l’inclusion / la transition">
                    <label class="form-check-label" for="transition">Agir pour l’inclusion / la transition</label>
                </div>
                <label class="form-label">Quels types de cours vous intéressent ? *</label>
                <input type="text" class="form-control" placeholder="Ex : Programmation, Marketing Digital, etc." name="type_cours" required>
            </div>

            <!-- Profil & niveau -->
            <div class="form-section">
                <h4>4. Profil & niveau</h4>
                <div class="mb-3">
                    <label for="niveauFormation" class="form-label">Niveau global de familiarité avec la formation en ligne *</label>
                    <select id="niveauFormation" class="form-select" name="niveau_formation" required>
                        <option value="">-- Sélectionner --</option>
                        <option>Je débute totalement</option>
                        <option>J’ai déjà suivi quelques cours</option>
                        <option>Je suis très à l’aise</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="etudes" class="form-label">Niveau d’étude ou de formation</label>
                    <select id="etudes" class="form-select" name="niveau_etude">
                        <option value="">-- Sélectionner --</option>
                        <option>Aucune formation formelle</option>
                        <option>Collège / lycée</option>
                        <option>Université / grandes écoles</option>
                        <option>Formation professionnelle</option>
                        <option>Autre</option>
                    </select>
                </div>
            </div>

            <!-- Accessibilité -->
            <div class="form-section">
                <h4>5. Accessibilité et conditions</h4>
                <div class="mb-3">
                    <label class="form-label">Avez-vous un accès régulier à Internet ? *</label>
                    <select class="form-select" name="acces_internet" required>
                        <option value="">-- Sélectionner --</option>
                        <option>Oui</option>
                        <option>Non</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apprenez-vous plutôt via…</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="appareil" id="smartphone" value="smartphone">
                        <label class="form-check-label" for="smartphone">Un smartphone</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="appareil" id="ordinateur" value="ordinateur">
                        <label class="form-check-label" for="ordinateur">Un ordinateur</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="appareil" id="lesDeux" value="les deux">
                        <label class="form-check-label" for="lesDeux">Les deux</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Besoins spécifiques d’accessibilité</label>
                    <input type="text" class="form-control" name="accessibilite" placeholder="Ex : lecture facile, sous-titres, gros caractères…">
                </div>
            </div>

            <!-- Consentements -->
            <div class="form-section">
                <h4>6. Consentements et finalisation</h4>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="rgpd" name="rgpd" required>
                    <label class="form-check-label" for="rgpd">
                        Je consens à la gestion de mes données selon la politique RGPD de Yitro Learning
                        <a href="#">(voir la politique)</a>
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="charte" name="charte" required>
                    <label class="form-check-label" for="charte">
                        Je m’engage à respecter la charte de bonne conduite de la communauté Yitro
                        <a href="#">(voir la charte)</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Créer mon compte</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php require_once './src/components/footer.php'?>

    <script src="<?= URL_ROOT ?>asset/js/inscription-apprenant.js"></script>
    <script>
        // JavaScript pour gérer l'animation et la visibilité de la barre de navigation
        const topNav = document.getElementById('top-nav');
        let lastScrollTop = 0;
        let isAnimating = false;
        let scrollTimeout = null;

        // Fonction de debouncing pour limiter les appels pendant le défilement
        function handleScroll() {
            if (isAnimating) return; // Évite les interférences pendant l'animation

            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > 100) {
                // Défilement vers le bas ou loin du haut
                if (!topNav.classList.contains('hidden')) {
                    isAnimating = true;
                    topNav.classList.add('hidden');
                    // Attendre la fin de l'animation (500ms) avant d'appliquer display: none
                    setTimeout(() => {
                        topNav.classList.add('invisible');
                        isAnimating = false;
                    }, 500);
                }
            } else {
                // Près du haut de la page
                if (topNav.classList.contains('hidden') || topNav.classList.contains('invisible')) {
                    isAnimating = true;
                    topNav.classList.remove('invisible'); // Rétablit display: flex
                    // Forcer un reflow pour que l'animation de réapparition fonctionne
                    topNav.offsetHeight; // Trigger reflow
                    topNav.classList.remove('hidden');
                    setTimeout(() => {
                        isAnimating = false;
                    }, 500);
                }
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }

        // Écouteur d'événement avec debouncing
        window.addEventListener('scroll', () => {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 100); // Délai de 100ms pour le debouncing
        });
    </script>
</body>
</html>