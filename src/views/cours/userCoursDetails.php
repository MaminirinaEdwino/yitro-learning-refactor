<?php
$cours_id = $params["cours_id"];
$utilisateur_id = $_SESSION['user_id'];
$cours = $params["cours"];
$lecons = $params["lecons"];
$modules = $params["modules"];
$completed_modules = $params["completed_module"];
$quiz = $params["quiz"];
$completed_quizzes = $params["completed_quizzes"];
$is_free = $params["is_free"];
$can_access = $params["can_access"];
$formateur = $params["formateur"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du cours - Yitro Learning</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/userCoursDetails.css">
</head>

<body>
    <?php require_once './src/components/headerapprenant.php' ?>

    <section class="course-details<?php echo $can_access ? ' course-free' : ''; ?>">
        <div class="container">
            <div class="course-info">
                <?php if ($cours->getPhoto()): ?>
                    <div class="course-image">
                        <img src="/Uploads/cours/<?php echo htmlspecialchars($cours->getPhoto()); ?>" alt="<?php echo htmlspecialchars($cours->getTitre()); ?>">
                    </div>
                <?php endif; ?>
                <div class="course-text">
                    <h1><?php echo htmlspecialchars($cours->getTitre()); ?></h1>
                    <div class="price"><?php echo number_format($cours->getPrix(), 2); ?> €</div>
                    <div class="course-description"><?php echo nl2br(htmlspecialchars($cours->getDescription())); ?></div>
                    <?php if ($formateur && $formateur->getNomPrenom()): ?>
                        <p class="formateur">Formateur : <?php echo htmlspecialchars($formateur->getNomPrenom()); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <h2>Modules et Leçons</h2>
            <?php if (empty($modules)): ?>
                <p>Aucun module disponible pour ce cours.</p>
            <?php else: ?>
                <?php foreach ($modules as $module): ?>
                    <div class="module">
                        <h3><?php echo htmlspecialchars($module->getTitre()); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($module->getDescription())); ?></p>
                        <?php if (isset($lecons[$module->getId()]) && is_array($lecons[$module->getId()]) && !empty($lecons[$module->getId()])): ?>
                            <?php foreach ($lecons[$module->getId()] as $index => $lecon): ?>
                                <div class="lecon">
                                    <strong><?php echo htmlspecialchars($lecon->getTitre()); ?></strong> (<?php echo strtoupper(htmlspecialchars($lecon->getFormat())); ?>)
                                    <?php
                                    $is_video = in_array(strtolower($lecon->getFormat()), ['video']);
                                    $is_audio = in_array(strtolower($lecon->getFormat()), ['audio']);
                                    $is_pdf = in_array(strtolower($lecon->getFormat()), ['pdf']);

                                    $filePath = URL_ROOT . "Uploads/lecons/" . rawurlencode($lecon->getFichier());

                                    ?>

                                    <div class="lesson-content">
                                        <?php if ($is_video): ?>
                                            <video controls width="600">
                                                <source src="<?php echo $filePath; ?>" type="video/mp4">
                                                Votre navigateur ne prend pas en charge la lecture de vidéos.
                                            </video>
                                        <?php elseif ($is_audio): ?>
                                            <audio controls>
                                                <source src="<?php echo $filePath; ?>" type="audio/mpeg">
                                                Votre navigateur ne prend pas en charge la lecture d'audio.
                                            </audio>
                                        <?php elseif ($is_pdf): ?>
                                            <a href="<?php echo $filePath; ?>" target="_blank">Voir le PDF</a>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($index === count($lecons[$module->getId()]) - 1): ?>
                                        <div class="completion-checkbox">
                                            <input type="checkbox" class="module-completion" data-module-id="<?php echo $module->getId(); ?>" data-cours-id="<?php echo $cours_id; ?>" <?php echo in_array($module->getId(), $completed_modules) ? 'checked' : ''; ?> onclick="">
                                            <label class="completion-label">Marquer comme terminé</label>
                                        </div>
                                        <p class="completion-message"></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="error-message">Aucune leçon disponible pour ce module.</p>
                        <?php endif; ?>
                        <?php if (isset($quiz[$module->getId()]) && is_array($quiz[$module->getId()]) && !empty($quiz[$module->getId()])): ?>
                            <div class="quiz-content">
                                <h4>Quiz</h4>
                                <?php foreach ($quiz[$module->getId()] as $q): ?>
                                    <div class="quiz">
                                        <strong><?php echo htmlspecialchars($q->getTitre()); ?></strong> (Score minimum : <?php echo htmlspecialchars($q->getScoreMinimum()); ?>%)
                                        <?php if ($can_access): ?>
                                            <div>
                                                <!-- <a href="take_quiz.php?id=<?php echo $q->getId(); ?>">Passer le quiz</a> -->
                                                <a href="/cours/quiz/apprenant/<?php echo $q->getId(); ?>">Passer le quiz</a>
                                                <?php if (in_array($q->getId(), $completed_quizzes[$module->getId()])): ?>
                                                    <span class="completed"> (Complété)</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (!$can_access): ?>
                <a href="#" class="btn-enroll" onclick="openPaymentModal()">S'inscrire au cours</a>
            <?php endif; ?>
        </div>
    </section>

    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <span class="close" onclick="closePaymentModal()">×</span>
            <h3><i class="fas fa-credit-card"></i> Paiement du cours</h3>

            <!-- <div class="payment-options" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button type="button" class="payment-btn active" id="cardBtn" style="border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; background-color: #9b8227; color: white;">
                    <i class="fas fa-credit-card"></i> Carte bancaire
                </button>
                <button type="button" class="payment-btn" id="mvolaBtn" style="border: 1px solid #9b8227; padding: 10px 20px; border-radius: 8px; cursor: pointer; background-color: white; color: #9b8227;">
                    <i class="fas fa-mobile-alt"></i> Mobile Money Mvola
                </button>
            </div> -->

            <form id="paymentFormCard" class="payment-form" action="/enroll/cours" method="post">
                <input type="hidden" name="cours_id" value="<?= $cours->getId() ?>">
                <div class="form-group">
                    <label for="card-number">Methode de payement</label>
                    
                    <input type="text" name="method_payement" id="" list="choice_list" placeholder="mvola ou virement bancaire">
                    <datalist id="choice_list" >
                        <option value="Virement bancaire">virement bancaire</option>
                        <option value="Mvola">Mvola</option>
                    </datalist>
                </div>
                
                <div class="form-group">
                    <label for="card-holder">Réference du payement</label>
                    <input type="text" id="card-holder" name="references_payement" placeholder="Réference du payement" required>
                </div>
                <button type="submit">Payer <?php echo number_format($cours->getPrix(), 2); ?> €</button>
                <p class="error" id="cardError" style="display: none; color: red;"></p>
                <p class="success" id="cardSuccess" style="display: none; color: green;"></p>
            </form>

            <!-- <form id="paymentFormMvola" class="payment-form" style="display: none;">
                <div class="form-group">
                    <label for="mvola-number"><i class="fas fa-mobile-alt"></i> Numéro Mobile Money Mvola</label>
                    <input type="text" id="mvola-number" name="mvola_number" placeholder="26134..." required>
                    <input type="hidden" name="cours_id" value="<?php echo htmlspecialchars($cours_id); ?>">
                    <input type="hidden" name="prix_cours" value="<?php echo htmlspecialchars($cours->getPrix()); ?>">
                </div>
                <button type="submit">Payer <?php echo number_format($cours->getPrix(), 2); ?> €</button>
                <p class="error" id="mvolaError" style="display: none; color: red;"></p>
                <p class="success" id="mvolaSuccess" style="display: none; color: green;"></p>
            </form> -->

        </div>
    </div>

    <?php require_once './src/components/footer.php' ?>

    <script src="<?= URL_ROOT ?>asset/js/userCoursDetails.js"></script>
</body>

</html>