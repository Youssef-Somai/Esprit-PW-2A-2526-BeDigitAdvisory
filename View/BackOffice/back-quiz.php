<?php
require_once '../../Controller/QuizC.php';

$quizC = new QuizC();


if (isset($_GET['id'])) {
    $quizC->deleteQuiz($_GET['id']);

    
    header('Location: back-quiz.php');
    exit();
}


$list = $quizC->listQuiz();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office | Gestion Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .sidebar { background: var(--dark); color: white; }
        .sidebar .menu-item { color: var(--gray-light); }
        .sidebar .menu-item:hover, .sidebar .menu-item.active { background: rgba(255,255,255,0.1); color: white; border-left-color: var(--accent); }
        .sidebar-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header .logo { color: white; }
        .user-profile-widget { background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.1); }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; transition: var(--transition); }
        .sidebar-header { padding: 1.5rem; display: flex; align-items: center; }
        .sidebar-menu { padding: 1rem 0; flex: 1; overflow-y: auto; }
        .menu-item { padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 1rem; font-weight: 500; cursor: pointer; transition: var(--transition); border-left: 3px solid transparent; text-decoration: none; }
        .menu-item i { width: 20px; text-align: center; font-size: 1.1rem; }
        .user-profile-widget { padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--accent); color: white; display: flex; justify-content: center; align-items: center; font-weight: 600; }
        .main-content { flex: 1; margin-left: 280px; padding: 2rem; background: #f1f5f9; min-height: 100vh; }
        .top-navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: white; padding: 1rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); }
        .card { background: white; border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-light); }
        .data-table th { color: var(--gray); font-weight: 500; }
        .badge { padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.85rem; font-weight: 500; display: inline-block; }
        .badge.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge.primary { background: rgba(37, 99, 235, 0.1); color: var(--primary); }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
        .quiz-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body class="admin-theme">
    <div class="dashboard-container">
        <!-- Sidebar ADMIN -->
        <aside class="sidebar admin-sidebar slide-in-right">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fa-solid fa-user-shield text-accent"></i> Admin Panel
                </div>
            </div>
            
            <div class="sidebar-menu">
                <a href="back-utilisateur.php" class="menu-item"><i class="fa-solid fa-users"></i> Gestion Utilisateurs</a>
                <a href="back-quiz.php" class="menu-item active"><i class="fa-solid fa-list-check"></i> Gestion Quiz</a>
                <a href="back-portfolio.php" class="menu-item"><i class="fa-solid fa-folder-open"></i> Gestion Portfolios</a>
                <a href="back-offres.php" class="menu-item"><i class="fa-solid fa-briefcase"></i> Gestion Offres</a>
                <a href="back-certification.php" class="menu-item"><i class="fa-solid fa-award"></i> Gestion Certifications</a>
                <a href="back-messagerie.php" class="menu-item"><i class="fa-solid fa-comments"></i> Gestion Messagerie</a>
            </div>

            <div class="user-profile-widget">
                <div class="user-avatar">AD</div>
                <div>
                    <h4 style="font-size: 0.95rem; margin-bottom: 0.2rem; color: white;">Admin Système</h4>
                    <span style="font-size: 0.8rem; color: var(--gray-light);">Admin</span>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="top-navbar">
                <h2 style="margin: 0; font-size: 1.5rem;">Administration - Rôle Superviseur</h2>
                <span class="badge warning" style="font-size: 1rem;"><i class="fa-solid fa-lock"></i> Espace Sécurisé Admin</span>
            </div>

            <section class="fade-in-up">
                <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2">
                    <h2>Gestion des Quiz</h2>
                    <button class="btn btn-primary" onclick="window.location.href='Create-quiz.php'">
                        <i class="fa-solid fa-plus"></i> Nouveau Quiz
                    </button>
                </div>

                <div class="card admin-card hover-zoom">
                    <h3>Liste des Quiz actifs</h3>
                    <table class="data-table mt-1">
                        <thead>
                            <tr>
                                <th>id_quiz</th>
                                <th>titre</th>
                                <th>description</th>
                                <th>image</th>
                                <th>date_creation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($list as $quiz) {
                            ?>
                            <tr>
                                <td><?php echo $quiz['id_quiz']; ?></td>
                                <td><?php echo $quiz['titre']; ?></td>
                                <td><?php echo $quiz['description']; ?></td>
                                <td>
                                    <img src="../../uploads/<?php echo $quiz['image']; ?>" alt="quiz" class="quiz-image">
                                </td>
                                <td><?php echo $quiz['date_creation']; ?></td>
                                <td>
                                <a href="questions.php?id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline btn-sm">
                                <i class="fa-solid fa-eye"></i> Questions
                                </a>

                                    <form method="POST" action="updateQuiz.php" style="display:inline-block;">
                                        <input type="hidden" value="<?php echo $quiz['id_quiz']; ?>" name="id_quiz">
                                        <button type="submit" name="update" class="btn btn-outline btn-sm">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </form>
                                <a href="back-quiz.php?id=<?php echo $quiz['id_quiz']; ?>"
                                      class="btn-delete"
                                    onclick="return confirm('Voulez-vous vraiment supprimer ce quiz ?');">
                                    <i class="fa-solid fa-trash"></i> Supprimer
                                </a>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>