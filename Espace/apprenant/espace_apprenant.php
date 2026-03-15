
<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../authentification/connexion.php");
    exit();
}

$stmt = $pdo->prepare("SELECT nom FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ../../authentification/connexion.php");
    exit();
}

// Récupérer tous les cours depuis la base de données
//$stmt = $pdo->prepare("SELECT * FROM cours");
//$stmt->execute();
//$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Récupérer toutes les formations pour le menu déroulant (Catégories)
$formations = [];
try {
    $stmt_formations = $pdo->prepare("SELECT id_formation, nom_formation FROM formations ORDER BY nom_formation");
    $stmt_formations->execute();
    $formations = $stmt_formations->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur de requête des formations : " . $e->getMessage());
}

// Récupérer tous les cours (y compris l'ID de formation pour le filtrage)
$stmt = $pdo->prepare("SELECT c.*, f.id_formation FROM cours c LEFT JOIN formations f ON c.formation_id = f.id_formation");
$stmt->execute();
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Espace Apprenant</title>
    <link rel="stylesheet" href="../../asset/css/styles.css">
    <link rel="icon" href="asset/images/Yitro_consulting.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once '../../components/headerapprenant.php'?>

    <div class="sidebar-toggle"><i class="fas fa-filter"></i> Filtres</div>
    <div class="main-content">
        <aside class="sidebar">
            <h3><i class="fas fa-filter"></i> Filtres</h3>
            <div class="filter-group">
                <h4><i class="fas fa-book"></i> Filtrer les cours</h4>
                <label for="category_filter">Catégories</label>
                <select id="category_filter">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($formations as $f): ?>
                        <option value="<?php echo $f['id_formation']; ?>"><?php echo htmlspecialchars($f['nom_formation']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="course-level">Niveau</label>
                <select id="course-level">
                    <option value="">Tous les niveaux</option>
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Avancé">Avancé</option>
                </select>
                <label>Prix</label>
                <div class="price-range">
                    <input type="range" id="price-min" min="0" max="500" value="0">
                    <input type="range" id="price-max" min="0" max="500" value="500">
                    <output>Prix: <span id="price-min-value">0</span> € - <span id="price-max-value">500</span> €</output>
                </div>
            </div>
            <div class="filter-group">
                <h4><i class="fas fa-comments"></i> Filtrer les forums</h4>
                <label for="forum-course">Cours associé</label>
                <select id="forum-course">
                    <option value="">Tous les cours</option>
                    <?php foreach ($cours as $c): ?>
                        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['titre']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="forum-keyword">Recherche par mot-clé</label>
                <input type="text" id="forum-keyword" placeholder="Mot-clé...">
            </div>
            <button id="apply-filters">Appliquer les filtres</button>
        </aside>
        <main class="content">
            <section class="courses-section">
                <h2 class="section-title">Nos Cours Disponibles</h2>
                <div class="courses-grid">
                    <?php if (empty($cours)): ?>
                        <p class="no-courses">Aucun cours disponible pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($cours as $c): ?>
                            <div class="course-card" 
                                data-theme-id="<?php echo htmlspecialchars($c['id_formation'] ?? ''); ?>" 
                                data-level="<?php echo htmlspecialchars($c['level'] ?? 'Débutant'); ?>" 
                                data-price="<?php echo $c['prix']; ?>"
                                data-title="<?php echo htmlspecialchars($c['titre']); ?>"
                                data-description="<?php echo htmlspecialchars($c['description']); ?>"
                            >
                                <div class="course-img">
                                    <?php if (!empty($c['photo'])): ?>
                                        <img src="../../Uploads/cours/<?php echo htmlspecialchars($c['photo']); ?>" alt="<?php echo htmlspecialchars($c['titre']); ?>">
                                    <?php else: ?>
                                        <img src="../../asset/images/default_course.jpg" alt="Image par défaut">
                                    <?php endif; ?>
                                </div>
                                <div class="course-content">
                                    <h3><?php echo htmlspecialchars($c['titre']); ?></h3>
                                    <p><?php echo htmlspecialchars(substr($c['description'], 0, 100)) . (strlen($c['description']) > 100 ? '...' : ''); ?></p>
                                    <div class="price"><?php echo number_format($c['prix'], 2); ?> €</div>
                                    <a href="cours_detail1.php?id=<?php echo $c['id']; ?>" class="btn-learn">Voir les détails</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <section class="forum-section">
                <h2 class="section-title">Forum de Discussion</h2>
                <div class="forum-form">
                    <h3>Créer un nouveau fil de discussion</h3>
                    <form action="forum.php" method="POST">
                        <input type="text" name="titre" placeholder="Titre du fil de discussion" required>
                        <textarea name="description" placeholder="Description du fil..." rows="4"></textarea>
                        <select name="cours_id" required>
                            <option value="">Sélectionner un cours</option>
                            <?php foreach ($cours as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['titre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Publier</button>
                    </form>
                </div>
                <div class="forum-grid">
                    <?php
                    // Récupérer les fils de discussion pour chaque cours
                    $stmt = $pdo->prepare("SELECT f.*, c.titre AS cours_titre FROM forum f JOIN cours c ON f.cours_id = c.id");
                    $stmt->execute();
                    $forums = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php if (empty($forums)): ?>
                        <p class="no-forum">Aucun fil de discussion disponible pour le moment.</p>
                    <?php else: ?>
                        <?php foreach ($forums as $forum): ?>
                            <div class="forum-card" data-course-id="<?php echo $forum['cours_id']; ?>" data-title="<?php echo htmlspecialchars($forum['titre']); ?>" data-description="<?php echo htmlspecialchars($forum['description']); ?>">
                                <h3><?php echo htmlspecialchars($forum['titre']); ?></h3>
                                <p>Dans le cours : <?php echo htmlspecialchars($forum['cours_titre']); ?></p>
                                <p><?php echo htmlspecialchars(substr($forum['description'], 0, 100)) . (strlen($forum['description']) > 100 ? '...' : ''); ?></p>
                                <a href="forum.php?forum_id=<?php echo $forum['id']; ?>" class="btn-forum">Participer à la discussion</a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <?php require_once '../../components/footer.php'?>

    <script>
        //gerer l'animation et la visibilité de la barre de navigation
        const topNav = document.querySelector('.main-nav');
        let lastScrollTop = 0;
        let isAnimating = false;
        let scrollTimeout = null;

        function handleScroll() {
            if (isAnimating) return;

            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > 100) {
                if (!topNav.classList.contains('hidden')) {
                    isAnimating = true;
                    topNav.classList.add('hidden');
                    setTimeout(() => {
                        topNav.classList.add('invisible');
                        isAnimating = false;
                    }, 500);
                }
            } else {
                if (topNav.classList.contains('hidden') || topNav.classList.contains('invisible')) {
                    isAnimating = true;
                    topNav.classList.remove('invisible');
                    topNav.offsetHeight;
                    topNav.classList.remove('hidden');
                    setTimeout(() => {
                        isAnimating = false;
                    }, 500);
                }
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        }

        window.addEventListener('scroll', () => {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 100);
        });

        //la sidebar et le filtrage
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        const applyFiltersButton = document.querySelector('#apply-filters');

        // FILTRES DE COURS
        const categoryFilter = document.querySelector('#category_filter'); 
        const courseLevel = document.querySelector('#course-level');
        const priceMin = document.querySelector('#price-min');
        const priceMax = document.querySelector('#price-max');
        const priceMinValue = document.querySelector('#price-min-value');
        const priceMaxValue = document.querySelector('#price-max-value');
        const courseCards = document.querySelectorAll('.course-card');

        // FILTRES DE FORUM
        const forumCourse = document.querySelector('#forum-course');
        const forumKeyword = document.querySelector('#forum-keyword');
        const forumCards = document.querySelectorAll('.forum-card'); 
        // Toggle sidebar on mobile
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });

        // Update price range values
        priceMin.addEventListener('input', () => {
            // S'assurer que le min ne dépasse pas le max
            if (parseFloat(priceMin.value) > parseFloat(priceMax.value)) {
                priceMax.value = priceMin.value;
            }
            priceMinValue.textContent = priceMin.value;
            priceMaxValue.textContent = priceMax.value;
        });

        priceMax.addEventListener('input', () => {
            // S'assurer que le max ne soit pas inférieur au min
            if (parseFloat(priceMax.value) < parseFloat(priceMin.value)) {
                priceMin.value = priceMax.value;
            }
            priceMinValue.textContent = priceMin.value;
            priceMaxValue.textContent = priceMax.value;
        });


        // Fonction principale de filtrage, appelée par le bouton
        function applyFilters() {
            //Recuperation des valeurs des filtres de cours
            const selectedThemeId = categoryFilter.value; 
            const selectedLevel = courseLevel.value;
            const minPrice = parseFloat(priceMin.value);
            const maxPrice = parseFloat(priceMax.value);

            //filtrer les cours
            let coursesVisibleCount = 0;
            courseCards.forEach(card => {
                const themeId = card.dataset.themeId || ''; 
                const level = card.dataset.level || '';
                const price = parseFloat(card.dataset.price) || 0;

                const matchesTheme = !selectedThemeId || themeId === selectedThemeId;
                const matchesLevel = !selectedLevel || level === selectedLevel;
                const matchesPrice = price >= minPrice && price <= maxPrice;

                if (matchesTheme && matchesLevel && matchesPrice) {
                    card.style.display = 'block';
                    coursesVisibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // recuperation des valeurs des filtres de forum
            const selectedForumCourse = forumCourse.value;
            const keyword = forumKeyword.value.toLowerCase();

            // filtrer les forums 
            forumCards.forEach(card => {
                const courseId = card.dataset.courseId || '';
                const title = card.dataset.title.toLowerCase();
                const description = card.dataset.description.toLowerCase();

                const matchesCourse = !selectedForumCourse || courseId === selectedForumCourse;
                const matchesKeyword = !keyword || title.includes(keyword) || description.includes(keyword);

                if (matchesCourse && matchesKeyword) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
        }

        applyFiltersButton.addEventListener('click', applyFilters);

        document.addEventListener('DOMContentLoaded', () => {
            applyFilters(); 
        });

    </script>
</body>
</html>
