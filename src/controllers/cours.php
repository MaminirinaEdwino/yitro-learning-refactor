<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/inscription.repositories.php";
require_once "./src/repositories/formateur.repositories.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/completions.repositories.php";
require_once "./src/repositories/quiz.repositories.php";
require_once "./src/repositories/requltatQuiz.repositories.php";
require_once "./src/repositories/lecon.repositories.php";
require_once "./src/repositories/formation.repositories.php";
require_once "./src/models/cours.php";
require_once "./src/models/module.php";
require_once "./src/models/lecons.php";

$coursRouter = new Router();

$coursRouter->post("/cours/new",  function () {
    $formateurId = $_SESSION['formateur_id'];
    $upload_dir =  './Uploads/cours/';
    $lecon_upload_dir = './Uploads/lecons/';
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $coursRepo = new CoursRepositories();
    $moduleRepo = new ModuleRepositories();
    $leconRepo = new LeconRepositories();



    // Types autorisés pour les leçons (PDF, MP3, MP4)
    $allowed_lecon_types = ['application/pdf', 'audio/mpeg', 'video/mp4', 'video/x-mp4', 'video/mpeg'];
    $max_size = 100 * 1024 * 1024; // 100MB pour un seul fichier

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    if (!is_dir($lecon_upload_dir)) {
        mkdir($lecon_upload_dir, 0755, true);
    }

    if (
        !isset($_POST['formation_id']) || !isset($_POST['contenu_formation_id']) || !isset($_POST['titre_cours'])
        || !isset($_POST['description_cours']) || !isset($_POST['prix_cours']) || !isset($_POST['niveau_cours'])
    ) {
        throw new Exception("Tous les champs obligatoires du cours doivent être remplis : Thème, Sous-Thème, Niveau, Titre, Description, Prix.");
    }

    $formation_id = $_POST['formation_id'];
    $contenu_formation_id = $_POST['contenu_formation_id'];
    $titre = $_POST['titre_cours'];
    $description = $_POST['description_cours'];
    $prix = $_POST['prix_cours'];
    $niveau = $_POST['niveau_cours'];
    $photo = null;

    if (isset($_FILES['photo_cours']) && $_FILES['photo_cours']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo_cours'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];
        $file_error = $file['error'];

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $photo = 'course_' . time() . '.' . $extension;
            $dest = $upload_dir . $photo;

            if (!move_uploaded_file($file_tmp, $dest)) {
                throw new Exception("Échec de l'upload de la photo vers $dest.");
            }
        } else {
            // Affiche l'erreur si le fichier est présent mais non valide
            throw new Exception("Photo: Type de fichier non autorisé ($file_type) ou taille excessive ($file_size octets). Erreur code: $file_error");
        }
    }

    $cours = new Cours($formateurId, $formation_id, $contenu_formation_id, $titre, $description, $prix, $photo, $niveau);
    $coursRepo->Insert($cours);
    $coursId = $coursRepo->GetLastInsertId($formateurId);
    echo $coursId;
    if (isset($_POST['modules']) && is_array($_POST['modules'])) {
        foreach ($_POST['modules'] as $module_index => $module) {
            // Un module nécessite au moins un titre et une description
            if (!isset($module['titre']) || !isset($module['description'])) {
                continue;
            }

            $module_titre = $module['titre'];
            $module_description = $module['description'];

            $newModule = new Module($coursId, $module_titre, $module_description);
            

            $module_id = $moduleRepo->Insert($newModule);

            // Gérer les leçons
            if (isset($module['lecons']) && is_array($module['lecons'])) {
                foreach ($module['lecons'] as $lecon_index => $lecon) {

                    // Vérification de la présence des champs de la leçon (titre et format)
                    if (!isset($lecon['titre']) || !isset($lecon['format'])) {
                        continue;
                    }

                    // Vérification de la présence du fichier de leçon (le champ est requis en HTML)
                    $file_key = $_FILES['modules']['name'][$module_index]['lecons'][$lecon_index]['fichier'] ?? null;

                    if (!$file_key) {
                        // Si le champ n'existe pas dans $_FILES, cela signifie qu'un fichier était requis mais non soumis.
                        throw new Exception("Fichier de leçon obligatoire manquant pour: " . htmlspecialchars($lecon['titre']));
                    }

                    $lecon_file = $file_key;
                    $lecon_tmp = $_FILES['modules']['tmp_name'][$module_index]['lecons'][$lecon_index]['fichier'];
                    $lecon_size = $_FILES['modules']['size'][$module_index]['lecons'][$lecon_index]['fichier'];
                    $lecon_type = $_FILES['modules']['type'][$module_index]['lecons'][$lecon_index]['fichier'];
                    $lecon_error = $_FILES['modules']['error'][$module_index]['lecons'][$lecon_index]['fichier'];

                    // GESTION ET UPLOAD DU FICHIER DE LEÇON
                    if ($lecon_error === UPLOAD_ERR_OK && $lecon_size <= $max_size && in_array($lecon_type, $allowed_lecon_types)) {
                        $lecon_extension = pathinfo($lecon_file, PATHINFO_EXTENSION);
                        $lecon_filename = 'lecon_' . $coursId . '_' . $module_id . '_' . time() . '_' . $lecon_index . '.' . $lecon_extension;
                        $lecon_dest = $lecon_upload_dir . $lecon_filename;

                        if (move_uploaded_file($lecon_tmp, $lecon_dest)) {

                            $newLecon =  new Lecons($module_id, $lecon['titre'], $lecon['format'], $lecon_filename);
                            $leconRepo->Insert($newLecon);
                        } else {
                            throw new Exception("Échec de l'upload du fichier de la leçon vers $lecon_dest. Vérifiez les permissions du dossier d'upload.");
                        }
                    } else if ($lecon_error === UPLOAD_ERR_NO_FILE) {
                        // Si l'erreur est "No file", on lève une exception car le champ est REQUIRED en HTML
                        throw new Exception("Fichier de leçon obligatoire non soumis pour: " . htmlspecialchars($lecon['titre']));
                    } else {
                        // Autres erreurs (taille, type, erreur interne)
                        throw new Exception("Erreur de fichier de leçon. Code: $lecon_error. Type: $lecon_type. Taille maximale: " . ($max_size / 1024 / 1024) . "MB");
                    }
                }
            }
        }
    }

    header("Location: /cours/formateur");
    exit;
});

$coursRouter->get("/cours/new", function () {
    $formationRepo = new FormationRepositories();

    $formations = $formationRepo->GetAllByNom();
    TemplateRender::render("/cours/create.php",  [
        "formations" => $formations
    ]);
});

$coursRouter->get("/cours/formateur", function () {
    $formateurId = $_SESSION["formateur_id"];
    $coursRepo = new CoursRepositories();
    $moduleRepo = new ModuleRepositories();
    $leconRepo = new LeconRepositories();
    $forumRepo = new ForumRepositories();

    $cours = $coursRepo->GetCoursFormationContenu($formateurId);

    $modules = [];
    $lecons = [];
    $forums = [];
    foreach ($cours as $c) {
        // Modules
        
        $modules[$c['id']] = $moduleRepo->GetByCoursId($c['id']);

        // Leçons
        foreach ($modules[$c['id']] as $module) {
            $id =  $module->getId();
            $lecons[$module->getId()] = $leconRepo->GetByModuleId($id);
        }

        // Forums
        $forums[$c['id']] = $forumRepo->GetByCoursId($c["id"]);
    }
    TemplateRender::render("/cours/list.php", [
        "cours" => $cours,
        "modules"=>$modules,
        "lecons"=>$lecons,
        "forums"=>$forums
    ]);
});

$coursRouter->get("/cours/apprenant/:id", function (int $coursId) {
    $userId = $_SESSION['user_id'];
    $coursRepo = new CoursRepositories();
    $inscriptionrepo = new InscriptionRepositories();
    $formateurRepo = new FormateurRepositories();
    $moduleRepo = new ModuleRepositories();
    $completionRepo = new CompletionsRepositories();
    $leconRepo = new LeconRepositories();
    $quizRepo = new QuizRepositories();
    $resultatQuizRepo = new ResultatQuizRepositories();


    if (!$_SESSION['user_id']) {
        header("location: /connexion");
    }

    $cours = $coursRepo->GetById($coursId);
    if (!$cours) {
        header("Location: espace_apprenant.php");
        exit();
    }
    $isEnrolled = $inscriptionrepo->GetEnrolledCours($userId, $coursId);
    $formateur = $formateurRepo->GetById($cours->getIdFormateur());
    $modules = $moduleRepo->GetByCoursId($cours->getId());
    $completedModule = $completionRepo->GetByCoursUserId($coursId, $userId);
    $lecons = [];
    $quiz = [];
    $completed_quizzes = [];
    foreach ($modules as $module) {
        $lecons[$module->getId()] = $leconRepo->GetByModuleId($module->getId());
        $quiz[$module->getId()] = $quizRepo->GetByModuleId($module->getId());
        $completed_quizzes[$module->getId()] = $resultatQuizRepo->GetByModuleId($userId, $module->getId());
    }
    $is_free = $cours->getPrix() == 0;
    $can_access = $is_free || $isEnrolled;
    TemplateRender::render("/cours/userCoursDetails.php", [
        "is_free" => $is_free,
        "can_access" => $can_access,
        "cours" => $cours,
        "formateur" => $formateur,
        "completed_module" => $completedModule,
        "completed_quizzes" => $completed_quizzes,
        "quiz" => $quiz,
        "lecons" => $lecons,
        "modules" => $modules
    ]);
});
