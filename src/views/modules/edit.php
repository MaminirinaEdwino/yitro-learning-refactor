<?php


$id = $params['id'];
$module = $params["module"];
// $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
// $stmt->execute([$id]);
// $module = $stmt->fetch(PDO::FETCH_ASSOC);

// if (!$module) {
//     die("Module non trouvé.");
// }


?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Yitro Learning - Modifier un module</title>
  <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/moduleEdit.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body>
  <?php require_once "./src/components/formateurSideBar.php" ?>
  <div class="main--content">
    <div class="header--wrapper">
      <div class="header--title">
        <span>Primary</span>
        <h2>Modifier un module</h2>
      </div>
      <div class="user--info">
        <div class="search--box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Rechercher...">
        </div>
        <!-- <img src="<?= URL_ROOT ?>asset/images/lito.jpg" alt="User Profile"> -->
      </div>
    </div>
    <h2>Modifier le module</h2>
    <form method="post">
      <div class="form-group">
        <label>Titre du module</label>
        <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($module->getTitre()) ?>" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" required><?= htmlspecialchars($module->getDescription()) ?></textarea>
      </div>
      <button type="submit" class="btn btn-success">Enregistrer</button>
      <a href="/cours/formateur" class="btn btn-secondary">Annuler</a>
    </form>
  </div>

  <script>
    gsap.from(".main--content", { opacity: 0, y: 50, duration: 1, ease: "power3.out" });
    gsap.from(".form-group", { opacity: 0, y: 20, duration: 0.8, stagger: 0.1, ease: "power2.out", delay: 0.2 });
    gsap.from(".btn", { opacity: 0, scale: 0.8, duration: 0.5, stagger: 0.1, ease: "back.out(1.7)", delay: 0.5 });
  </script>
</body>
</html>