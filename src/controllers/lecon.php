<?php

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/lecon.repositories.php";

$leconRouter = new Router();

$leconRouter->get("/lecon/edit/:id", function (int $leconId) {
    $leconRepo = new LeconRepositories();

    $lecon = $leconRepo->GetById($leconId);

    TemplateRender::render("/lecon/lecon.php", [
        "leconId" => $leconId,
        "lecon" => $lecon,
    ]);
});

$leconRouter->post("/lecon/edit/:id", function (int $leconId) {
    $leconRepo = new LeconRepositories();
    $lecon = $leconRepo->GetById($leconId);

    $titre = trim($_POST['titre']);
    $format = $_POST['format'];
    $fichier = $lecon->getFichier(); // Conserver l'ancien fichier par défaut

    // Gestion de l'upload de fichier
    if (!empty($_FILES['fichier']['name'])) {
        $allowed_extensions = [
            'pdf' => ['pdf'],
            'audio' => ['mp3', 'wav'],
            'video' => ['mp4', 'avi']
        ];

        $file = $_FILES['fichier'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Vérifier si l'extension correspond au format sélectionné
        if (!in_array($file_ext, $allowed_extensions[$format])) {
            $error = "Le fichier doit être au format " . implode(', ', $allowed_extensions[$format]) . " pour le format $format.";
        } else {
            // Créer le dossier Uploads/lecons si nécessaire
            $upload_dir =  './Uploads/lecons/';
            // if (!is_dir($upload_dir)) {
            //     mkdir($upload_dir, 0777, true);
            // }

            // Générer un nom de fichier unique
            $new_file_name = uniqid('lecon_') . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;

            // Déplacer le fichier
            if (move_uploaded_file($file_tmp, $destination)) {
                // Supprimer l'ancien fichier s'il existe
                if ($fichier && file_exists($upload_dir . $fichier)) {
                    unlink($upload_dir . $fichier);
                }
                $fichier = $new_file_name;
            } else {
                $error = "Erreur lors de l'upload du fichier.";
            }
        }
    }

    $lecon->setFichier($fichier);
    $lecon->setTitre($titre);
    $lecon->setFormat($format);

    // Mettre à jour la leçon
    if (!isset($error)) {
        try {
            $leconRepo->Update($lecon);
            // echo "od";
            header("Location: /cours/formateur");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
        }
    }
});

$leconRouter->get("/lecon/delete/:id", function (int $leconId) {
    $leconRepo  = new LeconRepositories();
    $lecon = $leconRepo->GetById($leconId);

    $leconRepo->Delete($lecon);

    header("Location: /cours/formateur");
    exit;
});
