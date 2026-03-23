
<?php
$forums = $params["forums"];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yitro Learning - Gestion des Forums</title>
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/styles/style-formateur.css">
    <link rel="stylesheet" href="<?= URL_ROOT ?>asset/css/gestionForum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        
    </style>
</head>
<body>
    <?php require_once './src/components/sidebaradmin.php'?>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Administration</span>
                <h2>Gestion des Forums</h2>
            </div>
            <div class="user--info">
                <div class="search--box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Rechercher...">
                </div>
                <img src="<?= URL_ROOT ?>asset/images/lito.jpg" alt="User Profile">
            </div>
        </div>

        <div class="forum--container">
            <h3>Liste des Forums</h3>
            <div class="table--wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Cours</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($forums)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Aucun forum disponible.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($forums as $forum): ?>
                                <tr class="table--row">
                                    <td><?php echo htmlspecialchars($forum['titre']); ?></td>
                                    <td><?php echo htmlspecialchars($forum['cours_titre']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($forum['date_creation'])); ?></td>
                                    <td class="display:flex; flex-wrap: wrap;">
                                        <a href="#" class="btn-action btn-edit" onclick="openEditModal(<?php echo $forum['id']; ?>, '<?php echo htmlspecialchars(addslashes($forum['titre'])); ?>', '<?php echo htmlspecialchars(addslashes($forum['description'])); ?>')">Modifier</a>
                                        <form action="/forum/delete/<?php echo $forum['id']; ?>" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="forum_id" value="<?php echo $forum['id']; ?>">
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Voulez-vous vraiment supprimer ce forum ?')">Supprimer</button>
                                        </form>
                                        <a href="/gestion/forum/message/<?php echo $forum['id']; ?>" class="btn-action btn-view"><i class="fas fa-eye"></i> Voir les messages</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier un forum -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">×</span>
            <h3>Modifier le Forum</h3>
            <form action="/forum/edit/" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="forum_id" id="edit_forum_id">
                <label for="edit_titre">Titre</label>
                <input type="text" name="titre" id="edit_titre" required>
                <label for="edit_description">Description</label>
                <textarea name="description" id="edit_description"></textarea>
                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </div>

    <script>
        // Fonctions pour gérer le modal
        function openEditModal(id, titre, description) {
            document.getElementById('edit_forum_id').value = id;
            document.getElementById('edit_titre').value = titre;
            document.getElementById('edit_description').value = description;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Fermer le modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }

        // Animations GSAP
        gsap.from(".header--wrapper", { 
            opacity: 0, 
            y: -20, 
            duration: 0.8, 
            ease: "power3.out" 
        });
        gsap.from(".forum--container", { 
            opacity: 0, 
            y: 30, 
            duration: 0.8, 
            ease: "power3.out",
            delay: 0.2 
        });
        gsap.from(".table--row", { 
            opacity: 0, 
            x: -20, 
            duration: 0.8, 
            stagger: 0.05, 
            ease: "power2.out",
            delay: 0.4 
        });
    </script>
</body>
</html>
