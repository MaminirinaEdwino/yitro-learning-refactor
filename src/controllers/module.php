<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/module.repositories.php";
require_once "./src/repositories/completions.repositories.php";
require_once "./src/repositories/lecon.repositories.php";

$moduleRouter = new Router();
$moduleRouter->get("/module/edit/:id", function (int $moduleId) {
    $moduleRepo = new ModuleRepositories();
    $module = $moduleRepo->GetById($moduleId);

    TemplateRender::render("/modules/edit.php", [
        "id" => $moduleId,
        "module" => $module
    ]);
});

$moduleRouter->post("/module/edit/:id", function (int $moduleId) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $moduleRepo = new ModuleRepositories();

    $module = $moduleRepo->GetById($moduleId);
    $module->setTitre($titre);
    $module->setDescription($description);
    $moduleRepo->Update($module);
    header("Location: /cours/formateur");
    exit;
});

$moduleRouter->get("/module/delete/:id", function (int $moduleId) {
    $completionRepo = new CompletionsRepositories();
    $leconRepo = new LeconRepositories();
    $moduleRepo = new ModuleRepositories();
    $module = $moduleRepo->GetById($moduleId);
    // Supprimer les enregistrements de la table completions liés à ce module
    $completionRepo->DeleteByModueId($moduleId);

    // Supprimer les leçons liées au module
    $leconRepo->DeleteByModuleId($moduleId);

    // Supprimer le module
    $moduleRepo->Delete($module);

    header("Location: /cours/formateur");
    exit;
});

$moduleRouter->get("/module/cours/:id", function (int $cours_id) {
    $moduleRepo = new ModuleRepositories();
    $modules = $moduleRepo->GetByCoursIdArray($cours_id);

    // echo $modules[0]["id"];
    header('Content-Type: application/json');
    echo json_encode($modules);
});


$moduleRouter->post("/module/complete/", function () {
    if (!AuthChecker("apprenant")) {
        header("Location: /connexion");
    }

    $moduleRepo = new ModuleRepositories();
    $completionRepo = new CompletionsRepositories(); 

    // Vérifier si les données POST sont présentes
    if (!isset($_POST['module_id']) || !isset($_POST['cours_id']) || !isset($_POST['is_checked'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit();
    }

    $utilisateur_id = $_SESSION['user_id'];
    $module_id = (int)$_POST['module_id'];
    $cours_id = (int)$_POST['cours_id'];
    $is_checked = filter_var($_POST['is_checked'], FILTER_VALIDATE_BOOLEAN);

    $module = $moduleRepo->GetByIdCoursId($module_id, $cours_id);

    // Vérifier si le module appartient au cours
   

    if (!$module) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Module ou cours invalide']);
        exit();
    }

    try {
        if ($is_checked) {
            // Ajouter la complétion

            $completionRepo->Insert(new Completions($utilisateur_id, $module_id, $cours_id));
            
            echo json_encode(['success' => true, 'message' => 'Module marqué comme terminé !']);
        } else {
            // Supprimer la complétion
            $completionRepo->DeleteByIdCoursUd($utilisateur_id, $module_id);
            
            echo json_encode(['success' => true, 'message' => 'Complétion annulée']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
    }
});
