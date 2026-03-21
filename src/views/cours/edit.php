<?php
$formateur_id = $_SESSION['formateur_id'];
$trainer_name = $_SESSION["formateur_nom_prenom"];

$id = $params["cours_id"];
$cours = $params["cours"];
$formations = $params["formations"];
$contenu_formations = $params["contenu_formation"];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Modifier un cours</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/editCours.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <?php require_once "./src/components/formateurSideBar.php"?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Modifier un cours</h2>
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
        <h2>Modifier le cours : <?php echo htmlspecialchars($cours['titre']); ?></h2>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="/cours/edit/<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="formation_id">Thème (Formation principale)</label>
                    <select name="formation_id" id="formation_id" class="form-control" required>
                        <option value="">Sélectionnez un thème</option>
                        <?php foreach ($formations as $f): ?>
                            <option value="<?php echo $f->getId_formation(); ?>" 
                                <?php echo ($f->getId_formation() == $cours['formation_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f->getNom_formation()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="contenu_formation_id">Sous-Thème (Contenu de formation)</label>
                    <select name="contenu_formation_id" id="contenu_formation_id" class="form-control" required>
                        <option value="">Chargement...</option> 
                    </select>
                </div>
            
                <div class="form-group">
                    <label for="titre">Titre du cours</label>
                    <input type="text" name="titre" id="titre" class="form-control" value="<?php echo htmlspecialchars($cours['titre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description du cours</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($cours['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="prix">Prix du cours (€)</label>
                    <input type="number" name="prix" id="prix" class="form-control" step="0.01" value="<?php echo htmlspecialchars($cours['prix']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="photo_cours">Photo du cours (jpg, jpeg, png)</label>
                    <?php if ($cours['photo']): ?>
                        <img src="<?= URL_ROOT ?>Uploads/cours/<?php echo htmlspecialchars($cours['photo']); ?>" alt="Photo du cours" class="course-image">
                    <?php endif; ?>
                    <input type="file" name="photo_cours" id="photo_cours" class="form-control" accept="image/jpeg,image/jpg,image/png">
                </div>
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                <a href="/cours/formateur" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formationSelect = document.getElementById('formation_id');
            const contenuSelect = document.getElementById('contenu_formation_id');
            const currentContenuId = <?php echo json_encode($cours['contenu_formation_id']); ?>;

            function loadSousFormations(formationId, initialLoad = false) {
                contenuSelect.innerHTML = '<option value="">Chargement...</option>';

                if (!formationId) {
                    contenuSelect.innerHTML = '<option value="">Sélectionnez d\'abord un thème</option>';
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('GET', '/sousformation/' + formationId, true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const sousFormations = JSON.parse(xhr.responseText);
                            
                            let optionsHtml = '<option value="">Sélectionnez un sous-thème</option>';
                            
                            if (Array.isArray(sousFormations) && sousFormations.length > 0) {
                                sousFormations.forEach(sf => {
                                    let selected = '';
                
                                    if (initialLoad && sf.id_contenu == currentContenuId) {
                                        selected = 'selected';
                                    }
                                    optionsHtml += `<option value="${sf.id_contenu}" ${selected}>${sf.sous_formation}</option>`;
                                });
                            } else {
                                optionsHtml = '<option value="">Aucun sous-thème trouvé pour ce thème</option>';
                            }
                            contenuSelect.innerHTML = optionsHtml;
                        } catch (e) {
                            contenuSelect.innerHTML = '<option value="">Erreur de parsing des données</option>';
                            console.error('Erreur de parsing JSON:', e);
                        }
                    } else {
                        contenuSelect.innerHTML = '<option value="">Erreur de chargement des données</option>';
                        console.error('Erreur AJAX:', xhr.statusText);
                    }
                };
                xhr.send();
            }

            // Si l'utilisateur change de Formation
            formationSelect.addEventListener('change', function() {
                // Si on change manuellement, on réinitialise la sélection (initialLoad = false)
                loadSousFormations(this.value, false); 
            });

            // Appeler la fonction au démarrage pour remplir le menu Sous-Thème
            loadSousFormations(formationSelect.value, true);
        });
   
        gsap.from(".main--content", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
        gsap.from(".form-group", { opacity: 0, y: 20, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.2 });
        gsap.from(".btn", { opacity: 0, scale: 0.8, duration: 0.5, stagger: 0.1, ease: "back.out(1.7)", delay: 0.5 });
        gsap.from(".course-image", { opacity: 0, scale: 0.9, duration: 0.7, ease: "power2.out", delay: 0.3 });
    </script>
</body>
</html>