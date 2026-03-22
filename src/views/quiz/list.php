<?php

$formateur_id = $_SESSION['formateur_id'];

// Récupérer le nom du formateur
$trainer_name = $_SESSION["formateur_nom_prenom"];
$quiz = $params["quiz"];

// Récupérer les quiz
// Supprimer un quiz

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Gestion des quiz</title>
    <link rel="stylesheet" href="../../asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="../../asset/css/quiz.css">
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
                <h2>Gestion des quiz</h2>
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
        <h1>Mes quiz</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (empty($quiz)): ?>
            <div class="no-quiz">
                Aucun quiz disponible. Créez votre premier quiz dès maintenant !
                <br>
                <a href="create_quiz.php" class="btn btn-primary mt-3">Créer un quiz</a>
            </div>
        <?php else: ?>
            <?php foreach ($quiz as $q): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($q['titre']); ?></h4>
                        <p class="card-text">
                            <strong>Cours :</strong> <?php echo htmlspecialchars($q['cours_titre']); ?><br>
                            <strong>Module :</strong> <?php echo htmlspecialchars($q['module_titre']); ?><br>
                            <strong>Nombre de questions :</strong> <?php echo $q['nb_questions']; ?><br>
                            <strong>Score minimum :</strong> <?php echo $q['score_minimum']; ?>%
                        </p>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($q['description'])); ?></p>
                        <a href="/quiz/edit/<?php echo $q['id']; ?>" class="btn btn-primary">Modifier</a>
                        <a href="/quiz/formateur/delete/<?php echo $q['id']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce quiz ?')">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="/quiz/new" class="btn btn-primary">Créer un nouveau quiz</a>
        <?php endif; ?>
    </div>

    <script>
        gsap.from(".main--content", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
        gsap.from(".card, .no-quiz", { opacity: 0, y: 30, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.2 });
        gsap.from(".btn", { opacity: 0, scale: 0.8, duration: 0.5, stagger: 0.1, ease: "back.out(1.7)", delay: 0.5 });
    </script>
</body>
</html>