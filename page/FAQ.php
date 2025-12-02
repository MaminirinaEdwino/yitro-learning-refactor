<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Yitro Learning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="../asset/images/Yitro consulting.png" type="image/png">
    <link rel="stylesheet" href="../asset/css/styles/style.css">
    <link rel="stylesheet" href="../asset/css/FAQ.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php require_once '../components/header.php'?>

    <!-- Section FAQ -->
    <main class="faq-section">
        <div class="container">
            <h1>Foire Aux Questions (FAQ)</h1>
            <!-- FAQ Apprenant -->
            <div class="faq-apprenant">
                <h2>FAQ pour les Apprenants</h2>
                <div class="All-question-reponse">
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Comment puis-je m'inscrire à une formation ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Pour vous inscrire à une formation, rendez-vous sur notre site web, choisissez la formation qui vous intéresse et suivez les instructions d'inscription.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Les cours sont-ils accessibles à vie ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Oui, une fois inscrit à un cours, vous y avez accès à vie, sauf indication contraire dans la description du cours. Vous pouvez revoir le contenu à tout moment.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Puis-je obtenir un certificat ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Oui, la plupart de nos cours offrent un certificat de réussite une fois que vous avez complété toutes les leçons et passé les évaluations avec succès.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Que faire si j'ai des problèmes techniques ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Si vous rencontrez des problèmes techniques, contactez notre support via l'adresse contact@yitro-consulting.com ou utilisez le formulaire de contact sur notre site.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Y a-t-il des prérequis pour les cours ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Les prérequis varient selon les cours. Consultez la description de chaque cours pour connaître les compétences ou connaissances requises avant de vous inscrire.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Formateur -->
            <div class="faq-formateur">
                <h2>FAQ pour les Formateurs</h2>
                <div class="All-question-reponse">
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Comment devenir formateur sur Yitro Learning ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Pour devenir formateur, rendez-vous sur la page "Devenir formateur", soumettez votre candidature avec vos qualifications et une proposition de cours. Notre équipe examinera votre dossier.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Quels types de cours puis-je proposer ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Vous pouvez proposer des cours dans n'importe quel domaine où vous avez une expertise, comme la technologie, les affaires, les langues, ou les compétences créatives. Assurez-vous que le contenu est structuré et engageant.</p>
                        </div>
                    </div>
                    <div class="faq-quest-rep">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            <h3>Comment interagir avec les apprenants ?</h3>
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="faq-reponse">
                            <p>Vous pouvez interagir avec les apprenants via des forums de discussion, des sessions de questions-réponses en direct, ou des commentaires sur les cours.</p>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php require_once '../components/footer.php'?>

    <script src="../asset/js/FAQ.js"></script>
</body>
</html>