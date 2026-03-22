<?php
$formateur_id = $_SESSION['formateur_id'];
$trainer_name = $_SESSION["formateur_nom_prenom"];
$cours = $params["cours"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Créer un quiz</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>
    <?php require_once "./src/components/formateurSideBar.php" ?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Créer un quiz</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <i class="fas fa-users"></i>
                <span class="trainer-name"><?php echo $trainer_name; ?></span>
            </div>
        </div>
        <h2>Créer un nouveau quiz</h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="/quiz/new" method="POST" id="quizForm">
            <div class="form-group">
                <label for="cours_id">Sélectionner un cours</label>
                <select name="cours_id" id="cours_id" class="form-control" required onchange="loadModules(this.value)">
                    <option value="">Choisir un cours</option>
                    <?php foreach ($cours as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['titre']); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($cours)): ?>
                    <div class="error">Aucun cours disponible. Veuillez créer un cours d'abord.</div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="module_id">Sélectionner un module</label>
                <select name="module_id" id="module_id" class="form-control" required>
                    <option value="">Choisir un module</option>
                </select>
                <div class="loading" id="module-loading">Chargement des modules...</div>
                <div class="no-modules" id="no-modules">Aucun module disponible pour ce cours. Veuillez créer un module d'abord.</div>
            </div>
            <div class="form-group">
                <label for="titre_quiz">Titre du quiz</label>
                <input type="text" name="titre_quiz" id="titre_quiz" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description_quiz">Description du quiz</label>
                <textarea name="description_quiz" id="description_quiz" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="score_minimum">Score minimum pour valider (%)</label>
                <input type="number" name="score_minimum" id="score_minimum" class="form-control" min="0" max="100" value="70" required>
            </div>
            <div id="questions-container"></div>
            <button type="button" class="btn btn-secondary mt-3" onclick="ajouterQuestion()">+ Ajouter une question</button>
            <button type="submit" class="btn btn-primary mt-3">Enregistrer le quiz</button>
        </form>
    </div>

    <script>
        let questionIndex = 0;

        function ajouterQuestion() {
            const questionsContainer = document.getElementById('questions-container');
            const questionHTML = `
                <div class="question-container" id="question-${questionIndex}">
                    <h5>Question ${questionIndex + 1}</h5>
                    <div class="form-group">
                        <label>Texte de la question</label>
                        <input type="text" name="questions[${questionIndex}][texte]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Réponse correcte</label>
                        <input type="text" name="questions[${questionIndex}][reponse_correcte]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Réponse incorrecte 1</label>
                        <input type="text" name="questions[${questionIndex}][reponse_incorrecte_1]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Réponse incorrecte 2</label>
                        <input type="text" name="questions[${questionIndex}][reponse_incorrecte_2]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Réponse incorrecte 3</label>
                        <input type="text" name="questions[${questionIndex}][reponse_incorrecte_3]" class="form-control" required>
                    </div>
                </div>
            `;
            questionsContainer.insertAdjacentHTML('beforeend', questionHTML);

            gsap.from(`#question-${questionIndex}`, {
                opacity: 0,
                y: 20,
                duration: 0.5,
                ease: "power2.out"
            });

            questionIndex++;
        }

        async function loadModules(coursId) {
            const moduleSelect = document.getElementById('module_id');
            const loading = document.getElementById('module-loading');
            const noModules = document.getElementById('no-modules');
            moduleSelect.innerHTML = '<option value="">Choisir un module</option>';
            loading.style.display = 'block';
            noModules.style.display = 'none';

            if (coursId) {
                try {
                    const response = await fetch(`/module/cours/${coursId}`);
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP ${response.status}: ${response.statusText}`);
                    }
                    const result = await response.json();
                    loading.style.display = 'none';

                    if (result.error) {
                        noModules.textContent = result.error;
                        noModules.style.display = 'block';
                    } else if (result.length === 0) {
                        noModules.textContent = 'Aucun module disponible pour ce cours. Veuillez créer un module d\'abord.';
                        noModules.style.display = 'block';
                    } else {
                        result.forEach(module => {
                            const option = document.createElement('option');
                            option.value = module.id;
                            option.textContent = module.titre;
                            moduleSelect.appendChild(option);
                        });
                        gsap.from('#module_id', {
                            opacity: 0,
                            y: 10,
                            duration: 0.5,
                            ease: "power2.out"
                        });
                    }
                } catch (error) {
                    loading.style.display = 'none';
                    noModules.textContent = `Erreur lors du chargement des modules : ${error.message}`;
                    noModules.style.display = 'block';
                    console.error('Erreur AJAX:', error);
                }
            } else {
                loading.style.display = 'none';
            }
        }

        // Validation du formulaire
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            const titre = document.getElementById('titre_quiz').value.trim();
            const moduleId = document.getElementById('module_id').value;
            if (!titre || !moduleId) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires : Titre et Module.');
                gsap.from(".error", {
                    opacity: 0,
                    y: 10,
                    duration: 0.5,
                    ease: "power2.out"
                });
            }
        });

        gsap.from(".main--content", {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out"
        });
        gsap.from(".form-group", {
            opacity: 0,
            y: 20,
            duration: 0.8,
            stagger: 0.1,
            ease: "power2.out",
            delay: 0.2
        });
        gsap.from(".btn", {
            opacity: 0,
            scale: 0.8,
            duration: 0.5,
            stagger: 0.1,
            ease: "back.out(1.7)",
            delay: 0.5
        });
    </script>
    
</body>

</html>