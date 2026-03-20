<?php
$quiz_id = $params["quiz_id"];
$quiz = $params['quiz'];


$is_enrolled = $params["is_enrolled"];
$is_free = $quiz['prix'] == 0;
$can_access = $is_free || $is_enrolled;

if (!$can_access) {
    header("Location: /cours/apprenant/".$quiz["cours_id"]);
    exit();
}

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