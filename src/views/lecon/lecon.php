<?php



$formateur_id = $_SESSION['formateur_id'];

// Récupérer le nom du formateur
$trainer_name = $_SESSION['formateur_nom_prenom'];
$id = $params["leconId"];
$lecon = $params["lecon"];




// Gérer la soumission du formulaire
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $titre = trim($_POST['titre']);
//     $format = $_POST['format'];
//     $fichier = $lecon['fichier']; // Conserver l'ancien fichier par défaut

//     // Gestion de l'upload de fichier
//     if (!empty($_FILES['fichier']['name'])) {
//         $allowed_extensions = [
//             'pdf' => ['pdf'],
//             'audio' => ['mp3', 'wav'],
//             'video' => ['mp4', 'avi']
//         ];

//         $file = $_FILES['fichier'];
//         $file_name = $file['name'];
//         $file_tmp = $file['tmp_name'];
//         $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

//         // Vérifier si l'extension correspond au format sélectionné
//         if (!in_array($file_ext, $allowed_extensions[$format])) {
//             $error = "Le fichier doit être au format " . implode(', ', $allowed_extensions[$format]) . " pour le format $format.";
//         } else {
//             // Créer le dossier Uploads/lecons si nécessaire
//             $upload_dir = __DIR__ . '/../../Uploads/lecons/';
//             if (!is_dir($upload_dir)) {
//                 mkdir($upload_dir, 0777, true);
//             }

//             // Générer un nom de fichier unique
//             $new_file_name = uniqid('lecon_') . '.' . $file_ext;
//             $destination = $upload_dir . $new_file_name;

//             // Déplacer le fichier
//             if (move_uploaded_file($file_tmp, $destination)) {
//                 // Supprimer l'ancien fichier s'il existe
//                 if ($fichier && file_exists($upload_dir . $fichier)) {
//                     unlink($upload_dir . $fichier);
//                 }
//                 $fichier = $new_file_name;
//             } else {
//                 $error = "Erreur lors de l'upload du fichier.";
//             }
//         }
//     }

//     // Mettre à jour la leçon
//     if (!isset($error)) {
//         try {
//             $stmt = $pdo->prepare("UPDATE lecons SET titre = ?, format = ?, fichier = ? WHERE id = ?");
//             $stmt->execute([$titre, $format, $fichier, $id]);
//             header("Location: liste_cours.php?success=Leçon modifiée avec succès");
//             exit;
//         } catch (PDOException $e) {
//             $error = "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
//         }
//     }
// }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Modifier une leçon</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/edit_lecon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
    <?php require_once "./src/components/formateurSideBar.php" ?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Primary</span>
                <h2>Modifier une leçon</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <i class="fas fa-user-circle"></i>
                <span class="trainer-name"><?php echo $trainer_name; ?></span>
            </div>
        </div>

        <div class="form-section">
            <h2>Modifier la leçon</h2>
            <?php if (isset($error)): ?>
                <div class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre de la leçon</label>
                    <input type="text" id="titre" name="titre" class="form-control" value="<?php echo htmlspecialchars($lecon->getTitre()); ?>" required>
                </div>
                <div class="form-group">
                    <label for="format">Format</label>
                    <select id="format" name="format" class="form-control" required>
                        <option value="pdf" <?php echo $lecon->getFormat() === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                        <option value="audio" <?php echo $lecon->getFormat() === 'audio' ? 'selected' : ''; ?>>Audio</option>
                        <option value="video" <?php echo $lecon->getFormat() === 'video' ? 'selected' : ''; ?>>Vidéo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fichier">Fichier</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="fichier" name="fichier" class="form-control">
                    </div>
                    <?php if ($lecon->getFichier()): ?>
                        <div class="current-file">Fichier actuel : <?php echo htmlspecialchars($lecon->getFichier()); ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                    <a href="/cours/formateur" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Animations GSAP
        gsap.from(".header--wrapper", { opacity: 0, y: -60, duration: 1.2, ease: "elastic.out(1, 0.5)" });
        gsap.from(".form-section", { opacity: 0, y: 40, duration: 1, ease: "power3.out", delay: 0.3 });
        gsap.from(".form-group", { opacity: 0, y: 30, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.5 });
        gsap.from(".btn", { opacity: 0, scale: 0.7, duration: 0.6, stagger: 0.1, ease: "back.out(2)", delay: 0.7 });
        gsap.from(".error, .success", { opacity: 0, x: -30, duration: 1, ease: "power3.out", delay: 0.3 });
        // Animation bouton survol
        document.querySelectorAll(".btn").forEach(btn => {
            btn.addEventListener("mouseenter", () => {
                gsap.to(btn, { scale: 1.05, boxShadow: "0 6px 12px rgba(0,0,0,0.2)", duration: 0.3 });
            });
            btn.addEventListener("mouseleave", () => {
                gsap.to(btn, { scale: 1, boxShadow: "0 4px 10px rgba(0,0,0,0.15)", duration: 0.3 });
            });
        });
    </script>
</body>
</html>