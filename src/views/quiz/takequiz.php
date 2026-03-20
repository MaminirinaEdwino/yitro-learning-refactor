<?php
// session_start();
// require_once '../config/db.php';

// // Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../../authentification/login.php");
//     exit();
// }

// // Récupérer le nom de l'utilisateur
// $stmt = $pdo->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
// $stmt->execute([$_SESSION['user_id']]);
// $user = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$user) {
//     header("Location: ../../authentification/login.php");
//     exit();
// }

// Vérifier si l'ID du quiz est fourni
// if (!isset($_GET['id'])) {
//     header("Location: espace_apprenant.php");
//     exit();
// }


// if ($quiz_id === false || $quiz_id <= 0) {
//     header("Location: espace_apprenant.php?error=ID du quiz invalide");
//     exit();
// }

// Récupérer les détails du quiz et du cours

$quiz_id = $params["quiz_id"];
// $stmt = $pdo->prepare("
//     SELECT q.*, m.cours_id, c.titre AS cours_titre, m.titre AS module_titre, c.prix
//     FROM quiz q
//     JOIN modules m ON q.module_id = m.id
//     JOIN cours c ON m.cours_id = c.id
//     WHERE q.id = ?
// ");
// $stmt->execute([$quiz_id]);
$quiz = $params['quiz'];

// if (!$quiz) {
//     header("Location: espace_apprenant.php?error=Quiz non trouvé");
//     exit();
// }

// Vérifier si l'utilisateur a accès au cours
// $stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE utilisateur_id = ? AND cours_id = ? AND statut_paiement = 'paye'");
// $stmt->execute([$_SESSION['user_id'], $quiz['cours_id']]);
$is_enrolled = $params["is_enrolled"];
$is_free = $quiz['prix'] == 0;
$can_access = $is_free || $is_enrolled;

if (!$can_access) {
    header("Location: /cours/apprenant/".$quiz["cours_id"]);
    exit();
}

// Récupérer les questions
// $stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
// $stmt->execute([$quiz_id]);
// $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$questions = $params["questions"];
// Traitement de la soumission finale via AJAX
$result_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_submission'])) {
    try {
        $score = 0;
        $total_questions = count($questions);
        $answers = json_decode($_POST['answers'], true);

        if ($total_questions > 0 && is_array($answers)) {
            foreach ($questions as $index => $question) {
                if (isset($answers[$index]) && $answers[$index] === $question['reponse_correcte']) {
                    $score++;
                }
            }
            $score_percentage = ($score / $total_questions) * 100;

            // Enregistrer le résultat
            $stmt = $pdo->prepare("
                INSERT INTO resultats_quiz (utilisateur_id, quiz_id, score, date)
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$_SESSION['user_id'], $quiz_id, $score_percentage]);

            $result_message = $score_percentage >= $quiz['score_minimum'] ?
                "Félicitations ! Vous avez obtenu $score_percentage% (Score minimum : {$quiz['score_minimum']}%)." :
                "Vous avez obtenu $score_percentage%. Le score minimum requis est {$quiz['score_minimum']}%. Veuillez réessayer.";
            echo json_encode(['success' => true, 'message' => $result_message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucune question ou réponses invalides.']);
        }
        exit();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement du résultat : ' . htmlspecialchars($e->getMessage())]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer le quiz - Yitro Learning</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/takeQuiz.css">
</head>

<body>
    <?php require_once './src/components/headerapprenant.php' ?>

    <section class="quiz-section">

        <div class="container">
            <h1><?php echo htmlspecialchars($quiz['titre']); ?> </h1>
           
            <div class="course-info">
                Cours : <?php echo htmlspecialchars($quiz['cours_titre']); ?> | Module : <?php echo htmlspecialchars($quiz['module_titre']); ?>
            </div>
            <div class="result-message" id="resultMessage" style="display: none;"></div>
            <form id="quizForm">
                <?php if (empty($questions)): ?>
                    <p>Aucune question disponible pour ce quiz. </p>
                <?php else: ?>
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="question" data-question-index="<?php echo $index; ?>">
                            <h3><?php echo ($index + 1) . '. ' . htmlspecialchars($q['texte']); ?></h3>
                            <div class="options">
                                <?php
                                // Mélanger les réponses
                                $options = [
                                    $q['reponse_correcte'],
                                    $q['reponse_incorrecte_1'],
                                    $q['reponse_incorrecte_2'],
                                    $q['reponse_incorrecte_3']
                                ];
                                shuffle($options);
                                foreach ($options as $option): ?>
                                    <label>
                                        <input type="radio" name="answer_<?php echo $index; ?>" value="<?php echo htmlspecialchars($option); ?>">
                                        <?php echo htmlspecialchars($option); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="button" class="btn-next" id="nextButton" disabled>Suivant</button>
                    <button type="button" class="btn-submit" id="submitButton" style="display: none;">Soumettre</button>
                    <a href="/cours/apprenant/<?php echo $quiz['cours_id']; ?>" class="btn-back">Retour au cours</a>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <script src="<?= URL_ROOT ?>asset/js/takeQuiz.js">

    </script>
</body>

</html>