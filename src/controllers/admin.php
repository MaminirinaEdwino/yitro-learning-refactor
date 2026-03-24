<?php

require_once './vendor/PHPMailer/src/Exception.php';
require_once './vendor/PHPMailer/src/PHPMailer.php';
require_once './vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./src/router/router.php";
require_once "./src/templaterender/templateRender.php";
require_once "./src/repositories/utilisateurs.repositories.php";
require_once "./src/repositories/formateur.repositories.php";
require_once "./src/repositories/cours.repositories.php";
require_once "./src/repositories/journalActivite.repositories.php";

$adminRouter = new Router();
$adminRouter->get("/admin/login", function () {
    TemplateRender::render("/admin/login.php", null);
});

$adminRouter->post("/admin/login", function () {
    $userRepo = new UtilisateursRepositories();
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($email) || empty($mot_de_passe)) {
        header("Location: /admin/login");
        exit();
    } else {

        $result = $userRepo->GetForAuthAdmin($email);

        if ($result) {
            $user = $result;
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                header("Location: /admin/backoffice");
                exit();
            } else {
                $_SESSION['error'] = "Mot de passe incorrect.";
            }
        } else {
            $_SESSION['error'] = "Aucun compte administrateur trouvé avec cet email.";
        }
    }
});

$adminRouter->get("/admin/backoffice", function () {
    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();
    $coursRepo = new CoursRepositories();
    $journalRepo = new JournalActiviteRepositories;

    $newApprenant = $userRepo->GetNewApprenant();
    $newFormateur = $formateurRepo->GetNewFormateur();
    $newCours = $coursRepo->GetNewCours();
    $inactive_users = $userRepo->GetInactiveUsers();
    $formateurs = $formateurRepo->GetFormateurId();
    $inscriptions = [];
    for ($i = 5; $i >= 0; $i--) {
        $mois = date('Y-m', strtotime("-$i months"));
        $inscriptions[$mois] = $userRepo->CountUserPerMonth($mois);
    }

    TemplateRender::render("/admin/backoffice.php", [
        "new_apprenant" => $newApprenant,
        "new_formateurs" => $newFormateur,
        "new_cours" => $newCours,
        "inactive_users" => $inactive_users,
        "formateurs" => $formateurs,
        "apprenant_count" => $userRepo->CountApprenant(),
        "formateurs_count" => $formateurRepo->CountFormateur(),
        "cours_count" => $coursRepo->CountCours(),
        "activite_log" => $journalRepo->CountLog(),
        "last_log" => $journalRepo->GetLastLog(),
        "inscriptions" => $inscriptions
    ]);
});

$adminRouter->post("/formateur/update/status/:id", function (int $id) {
    $statut = $_POST['statut'];
    $formateurRepo = new FormateurRepositories();

    $formateurRepo->UpdateStatus($id, $statut, $_SESSION['user_id']);
    header("Location: /admin/gestionuser");
    exit();
});

$adminRouter->get("/admin/gestionuser", function () {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    $sort_column = $sort;
    if ($sort == 'nom') {
        $sort_column_utilisateurs = 'nom';
        $sort_column_formateurs = 'nom_prenom';
    } else {
        $sort_column_utilisateurs = $sort;
        $sort_column_formateurs = $sort;
    }

    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();

    $users = $userRepo->GetUserGestionUser($search, $sort_column_utilisateurs, $order);
    $formateurs = $formateurRepo->GetForamteurGestionUser($search, $sort_column_formateurs, $order);
    $admins = $userRepo->GetAdminGestionUser($search, $sort_column_utilisateurs, $order);
    TemplateRender::render("/admin/gestionutilisateur.php", [
        "search" => $search,
        "sort" => $sort,
        "order" => $order,
        "users" => $users,
        "formateurs" => $formateurs,
        "admins" => $admins
    ]);
});


$adminRouter->get("/export/csv", function () {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
    $order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

    // $sort_column = $sort;
    if ($sort == 'nom') {
        $sort_column_utilisateurs = 'nom';
        $sort_column_formateurs = 'nom_prenom';
    } else {
        $sort_column_utilisateurs = $sort;
        $sort_column_formateurs = $sort;
    }

    $userRepo = new UtilisateursRepositories();
    $formateurRepo = new FormateurRepositories();

    $users = $userRepo->GetUserGestionUser($search, $sort_column_utilisateurs, $order);
    $formtrs = $formateurRepo->GetForamteurGestionUser($search, $sort_column_formateurs, $order);
    $admins = $userRepo->GetAdminGestionUser($search, $sort_column_utilisateurs, $order);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=utilisateurs.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Type', 'ID', 'Nom', 'Email', 'Statut/Actif']);

    foreach ($users as $user) {
        fputcsv($output, ['Apprenant', $user['id'], $user['nom'], $user['email'], $user['actif'] ? 'Actif' : 'Inactif']);
    }
    foreach ($formtrs as $formtr) {
        fputcsv($output, ['Formateur', $formtr['id'], $formtr['nom_prenom'], $formtr['email'], $formtr['statut']]);
    }
    foreach ($admins as $admin) {
        fputcsv($output, ['Administrateur', $admin['id'], $admin['nom'], $admin['email'], $admin['actif'] ? 'Actif' : 'Inactif']);
    }
    fclose($output);
    exit;
});


$adminRouter->post("/send/code", function () {
    $formateur_id = $_POST['id'];
    // Générer un code unique
    $code = bin2hex(random_bytes(8)); // Code aléatoire de 16 caractères

    $formateurRepo = new FormateurRepositories();
    $journalRepo = new JournalActiviteRepositories();
    $formateur = $formateurRepo->GetById($formateur_id);


    if ($formateur) {
        // Mettre à jour le code dans la base de données
        $formateurRepo->UpdateCode($formateur_id, $code);

        // Envoyer l'email avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'edwinomaminirina@gmail.com'; // Remplacez par votre adresse Gmail
            $mail->Password = ''; // Remplacez par le mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Désactiver le débogage
            $mail->SMTPDebug = 0; // 0 = désactivé
            $mail->Debugoutput = 'html'; // Sortie par défaut (non utilisée si SMTPDebug = 0)

            // Destinataire
            $mail->setFrom('edwinobig@gmail.com', 'Yitro Learning');
            $mail->addAddress($formateur->getEmail());

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = 'Votre code d\'inscription à Yitro Learning';
            $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd;'>
                        <img src='https://yitro-consulting.com/wp-content/uploads/2024/02/Capture-decran-le-2024-02-19-a-16.39.58.png' alt='Yitro Learning' style='max-width: 150px;'>
                        <h2>Votre code d'inscription</h2>
                        <p>Bonjour,</p>
                        <p>Voici votre code d'inscription pour devenir formateur sur Yitro Learning :</p>
                        <p style='font-size: 24px; font-weight: bold; color: #28a745;'>$code</p>
                        <p>Utilisez ce code pour finaliser votre inscription sur <a href='https://yitro-learning.com/Page/inscription-formateur.php' style='color: #007bff;'>notre plateforme</a>.</p>
                        <p>Merci de rejoindre notre communauté !</p>
                        <p style='color: #888;'>L'équipe Yitro Learning</p>
                    </div>
                ";
            $mail->AltBody = "Bonjour,\n\nVoici votre code d'inscription pour devenir formateur sur Yitro Learning : $code\n\nUtilisez ce code pour finaliser votre inscription sur https://yitro-learning.com/Page/inscription-formateur.php.\n\nMerci de rejoindre notre communauté !\nL'équipe Yitro Learning";

            $mail->send();
            $success_message = "Code envoyé avec succès à {$formateur->getEmail()}.";

            $admin_id = $_SESSION["user_id"];
            // Journaliser l'action
            $journalRepo->Insert(new JournalActivite($admin_id, 'Envoi code formateur', "Formateur ID: $formateur_id, Email: {$formateur->getEmail()}, Code: $code"));

            error_log("Email envoyé à {$formateur['email']} avec code: $code");
        } catch (Exception $e) {
            $error_message = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
            error_log("Erreur envoi email à {$formateur['email']}: {$mail->ErrorInfo}");
        }
    } else {
        $error_message = "Formateur non trouvé.";
        error_log("Formateur ID $formateur_id non trouvé");
    }
    header("Location: /admin/gestionuser");
    exit;
});

$adminRouter->get("/suivi/apprenant/:id", function (int $id) {
    $userRepo = new UtilisateursRepositories();

    TemplateRender::render("/admin/suiviApprenant.php", [
        "id" => $id,
        "user" => $userRepo->GetById($id)
    ]);
});

$adminRouter->get("/voir/user/:id", function (int $id) {
    $userRepo = new UtilisateursRepositories();

    TemplateRender::render("/admin/voirApprenant.php", [
        "id" => $id,
        "user" => $userRepo->GetById($id)
    ]);
});

$adminRouter->get("/voir/formateur/:id", function (int $id) {
    $formateurRepo = new FormateurRepositories();

    TemplateRender::render("/admin/voirFormateur.php", [
        "id" => $id,
        "formateur" => $formateurRepo->GetById2($id)
    ]);
});

$adminRouter->get("/controle/qualite/:id", function (int $id) {
    $formateurRepo = new FormateurRepositories();
    $coursRepo  = new CoursRepositories();
    $moduleRepo = new ModuleRepositories();
    $leconRepo = new LeconRepositories();

    $cours = $coursRepo->GetCoursByFormateur($id);
    foreach ($cours as &$c) {
        $c['modules'] = $moduleRepo->GetByCoursId2($c['id']);
        foreach ($c['modules'] as &$m) {
            $m['lecons'] = $leconRepo->GetByModuleId2($m['id']);
        }
    }

    TemplateRender::render("/admin/controleQualite.php", [
        "id" => $id,
        "formateur" => $formateurRepo->GetById2($id),
        "cours" => $cours
    ]);
});

$adminRouter->get("/formateur/delete/:id", function (int $id) {
    $formateurRepo = new FormateurRepositories();
    $formateurRepo->Delete($formateurRepo->GetById($id));
    header("Location: /admin/gestionuser");
    exit();
});

$adminRouter->get("/admin/progression/apprenant", function () {
    $userRepo = new UtilisateursRepositories();
    $inscriptionsRepo = new InscriptionRepositories();
    $moduleRepo = new ModuleRepositories();
    $completionRepo = new CompletionsRepositories();
    $quizRepo = new QuizRepositories();
    $resultatQuizRepo = new ResultatQuizRepositories();
    $journalRepo = new JournalActiviteRepositories();
    $apprenants = $userRepo->GetActiveUser();

    // Récupérer les apprenants actifs


    // Pour chaque apprenant, récupérer ses cours, progression des cours et des quiz
    foreach ($apprenants as &$apprenant) {
        $apprenant["cours"] = $inscriptionsRepo->GetProgressionApprenant($apprenant["id"]);


        foreach ($apprenant['cours'] as &$cours) {
            // Progression des cours (modules complétés)
            $total_modules = $moduleRepo->GetTotalModule($cours['cours_id']);
            $modules_completes = $completionRepo->GetCompleteModule($apprenant['id'], $cours['cours_id']);

            $cours['progression_cours'] = $total_modules > 0 ? round(($modules_completes / $total_modules) * 100) : 0;
            
            $total_quiz = $quizRepo->GetTotalQuiz($cours['cours_id']);
            // Progression des quiz (quiz réussis)
            
            $quiz_reussis = $resultatQuizRepo->GetQuizReussis($apprenant['id'], $cours['cours_id']);
            

            $cours['progression_quiz'] = $total_quiz > 0 ? round(($quiz_reussis / $total_quiz) * 100) : 0;
        }
    }
    unset($apprenant, $cours);

    // Enregistrer l'activité dans journal_activite
    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], "voir progression", "voir progression apprenant"));

    TemplateRender::render("/admin/progressionApprenant.php", [
        "apprenants"=>$apprenants
    ]);
});

$adminRouter->get("/espace/certificat", function(){
    $userRepo = new UtilisateursRepositories();
    $apprenants = $userRepo->GetActiveUser();
    TemplateRender::render("/admin/espacecertificat.php", [
        "apprenants"=>$apprenants
    ]);
});

$adminRouter->post("/espace/certificat", function(){
    $apprenant_id = filter_input(INPUT_POST, 'apprenant_id', FILTER_VALIDATE_INT);
    $cours_id = filter_input(INPUT_POST, 'cours_id', FILTER_VALIDATE_INT);
    $titre_certificat = trim($_POST['titre_certificat'] ?? 'Certificat de Réussite');
    $date_emission = trim($_POST['date_emission'] ?? date('Y-m-d'));
    $userRepo = new UtilisateursRepositories();
    $moduleRepo =new ModuleRepositories();
    $completionRepo = new CompletionsRepositories();
    $journalRepo = new JournalActiviteRepositories();
    $admin_id = $_SESSION['user_id'];
    $userRepo = new UtilisateursRepositories();
    $apprenants = $userRepo->GetActiveUser();
    $download_link = '';
    $message = "";
    // Validation des entrées
    if (!$apprenant_id || !$cours_id) {
        $message = "Erreur : Sélection d'apprenant ou de cours invalide.";
    } else {
        $info = $userRepo->GetInfoUserCertificat($apprenant_id, $cours_id);
        if (!$info) {
            $message = "Erreur : L'apprenant n'est pas inscrit à ce cours ou le paiement n'est pas validé.";
        } else {
            // Vérifier l'éligibilité (100% des modules complétés)
            
            $total_modules = $moduleRepo->GetTotalModule($cours_id);

            $modules_completes = $completionRepo->GetCompleteModule($apprenant_id, $cours_id);

            if ($total_modules > 0 && $modules_completes == $total_modules) {
                // Échapper les caractères pour affichage
                $nom_apprenant = htmlspecialchars($info['nom'], ENT_QUOTES, 'UTF-8');
                $titre_cours = htmlspecialchars($info['titre'], ENT_QUOTES, 'UTF-8');
                $titre_certificat = htmlspecialchars($titre_certificat, ENT_QUOTES, 'UTF-8');
                $filename = "certificat_" . str_replace(' ', '_', $info['nom']) . "_" . str_replace(' ', '_', $info['titre']) . ".pdf";
                $output_dir ='./Upload/certificats/';
                $logo_path = './asset/images/lito.jpg';
                $signature_path = './asset/images/signature.jpg'; // Chemin de la signature

                // Vérifier et créer le dossier
                if (!is_dir($output_dir)) {
                    if (!mkdir($output_dir, 0777, true)) {
                        $message = "Erreur : Impossible de créer le dossier certificats.";
                        
                        $journalRepo->Insert(new JournalActivite($admin_id, 'Erreur génération certificat', "Échec création dossier certificats"));
                    }
                }

                // Tester l'écriture dans le dossier
                $test_file = $output_dir . 'test.txt';
                if (!file_put_contents($test_file, "Test d'écriture")) {
                    $message = "Erreur : Impossible d'écrire dans le dossier certificats. Détails : " . json_encode(error_get_last());

                    $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Erreur génération certificat', "Échec écriture dossier pour {$info['nom']} - {$info['titre']}: " . json_encode(error_get_last())));
                } else {
                    unlink($test_file); // Supprimer le fichier test

                    // Vérifier le logo
                    $logo_error = '';
                    if (!file_exists($logo_path)) {
                        $logo_error = "Logo introuvable : $logo_path";
                    } elseif (!is_readable($logo_path)) {
                        $logo_error = "Logo non lisible (permissions) : $logo_path";
                    }

                    // Vérifier la signature
                    $signature_error = '';
                    if (!file_exists($signature_path)) {
                        $signature_error = "Signature introuvable : $signature_path";
                    } elseif (!is_readable($signature_path)) {
                        $signature_error = "Signature non lisible (permissions) : $signature_path";
                    }

                    // Initialiser TCPDF en paysage
                    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor('Yitro Learning');
                    $pdf->SetTitle($titre_certificat);
                    $pdf->SetMargins(15, 15, 15);
                    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                    $pdf->AddPage();

                    // Police Times pour un look élégant
                    $pdf->SetFont('times', '', 12);

                    // Bordure double
                    $pdf->SetLineStyle(array('width' => 1, 'color' => array(1, 174, 143)));
                    $pdf->Rect(15, 15, 267, 180);
                    $pdf->SetLineStyle(array('width' => 0.5, 'color' => array(1, 174, 143)));
                    $pdf->Rect(16, 16, 265, 178);

                    // Rectangle interne subtil
                    $pdf->SetLineStyle(array('width' => 0.2, 'color' => array(1, 174, 143)));
                    $pdf->Rect(25, 30, 247, 140);

                    // Filigrane (logo en transparence)
                    if ($logo_error === '') {
                        try {
                            $pdf->SetAlpha(0.07);
                            $pdf->Image($logo_path, 108.5, 65, 80, 0, '', '', 'T', false, 300, '', false, false, 0);
                            $pdf->SetAlpha(1);
                        } catch (Exception $e) {
                            // Ignorer l'erreur du filigrane
                        }
                    }

                    // Logo principal (centré en haut)
                    if ($logo_error === '') {
                        try {
                            $pdf->Image($logo_path, 118.5, 15, 60, 0, '', '', 'T', false, 300, '', false, false, 0);
                        } catch (Exception $e) {
                            $logo_error = "Erreur TCPDF pour le logo : " . $e->getMessage();
                        }
                    }
                    if ($logo_error !== '') {
                        $pdf->SetXY(118.5, 15);
                        $pdf->SetFont('times', 'B', 16);
                        $pdf->Cell(0, 10, 'Yitro Learning Logo', 0, 1, 'C');
                        
                        $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Erreur génération certificat', $logo_error));
                    }

                    // Lignes décoratives autour du titre
                    $pdf->SetLineStyle(array('width' => 0.3, 'color' => array(1, 174, 143)));
                    $pdf->Line(40, 45, 257, 45);
                    $pdf->Line(40, 55, 257, 55);

                    // Contenu du certificat
                    $pdf->SetY(50);
                    $pdf->SetFont('times', 'B', 30);
                    $pdf->SetTextColor(1, 174, 143);
                    $pdf->Cell(0, 10, $titre_certificat, 0, 1, 'C');

                    $pdf->SetY(60);
                    $pdf->SetFont('times', '', 16);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(0, 10, 'Ce certificat est décerné à', 0, 1, 'C');

                    // Nom de l'apprenant (fixe, gère longs et courts)
                    $pdf->SetY(70);
                    $pdf->SetFont('times', 'B', 32);
                    $pdf->SetTextColor(1, 174, 143);
                    $pdf->MultiCell(240, 10, $nom_apprenant, 0, 'C', false, 1, 28.5, 70, true, 0, false, true, 10, 'M');

                    // Ligne décorative
                    $pdf->SetLineStyle(array('width' => 0.5, 'color' => array(1, 174, 143)));
                    $pdf->Line(50, 85, 247, 85);

                    $pdf->SetY(95);
                    $pdf->SetFont('times', '', 16);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(0, 10, 'pour avoir complété avec succès le cours', 0, 1, 'C');

                    $pdf->SetY(105);
                    $pdf->SetFont('times', 'B', 24);
                    $pdf->Cell(0, 10, $titre_cours, 0, 1, 'C');

                    $pdf->SetY(115);
                    $pdf->SetFont('times', '', 16);
                    $pdf->Cell(0, 10, 'Date d\'émission : ' . $date_emission, 0, 1, 'C');

                    // Signature (image)
                    if ($signature_error === '') {
                        try {
                            $pdf->Image($signature_path, 230, 160, 25, 0, '', '', 'T', false, 300, '', false, false, 0);
                            // Rectangle de débogage temporaire
                            $pdf->SetLineStyle(array('width' => 0.2, 'color' => array(255, 0, 0)));
                            $pdf->Rect(230, 160, 25, 10, 'D');
                        } catch (Exception $e) {
                            $signature_error = "Erreur TCPDF pour la signature : " . $e->getMessage();
                        }
                    }
                    if ($signature_error !== '') {
                        $pdf->SetXY(230, 160);
                        $pdf->SetFont('times', 'I', 14);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(0, 10, 'Signature : Yitro Learning', 0, 1, 'R');
                        $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Erreur génération certificat', $signature_error));
                    }

                    $pdf->SetY(170);
                    $pdf->SetFont('times', 'B', 14);
                    $pdf->SetTextColor(1, 174, 143);
                    $pdf->Cell(0, 10, 'Yitro Learning', 0, 1, 'C');

                    // Sauvegarder le PDF
                    $pdf_file = $output_dir . $filename;
                    try {
                        $pdf->Output($pdf_file, 'F');
                        $download_link = "./Upload/certificats/" . $filename;
                        $message = "Certificat généré pour {$info['nom']} - {$info['titre']}.";
                        
                        $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Génération certificat', "Certificat pour {$info['nom']} - {$info['titre']}"));
                    } catch (Exception $e) {
                        $message = "Erreur : Impossible de sauvegarder le fichier PDF. Détails : " . $e->getMessage();
                        
                        $journalRepo->Insert(new JournalActivite($_SESSION['user_id'], 'Erreur génération certificat', "Échec sauvegarde PDF pour {$info['nom']} - {$info['titre']}: " . $e->getMessage()));
                    }
                }
            } else {
                $message = "Erreur : L'apprenant n'a pas complété tous les modules du cours.";
            }
        }
    }
    TemplateRender::render("/admin/espacecertificat.php", [
        "message"=>$message,
        "link"=>$download_link,
        "apprenants"=>$apprenants
    ]);
});

$adminRouter->get("/apprenants/cours/:id", function(int $id){
    $inscriptionsRepo = new InscriptionRepositories();
    $cours = $inscriptionsRepo->GetApprenantCours($id);
    header('Content-Type: application/json');
    echo json_encode($cours);
});