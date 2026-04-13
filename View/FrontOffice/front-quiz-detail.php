<?php
require_once __DIR__ . '/../../config.php';

if (!isset($_GET['id'])) {
    die('Quiz introuvable');
}

$idQuiz = (int) $_GET['id'];
$db = config::getConnexion();

$sqlQuiz = "SELECT * FROM quiz WHERE id_quiz = :id";
$queryQuiz = $db->prepare($sqlQuiz);
$queryQuiz->execute(['id' => $idQuiz]);
$quiz = $queryQuiz->fetch(PDO::FETCH_ASSOC);

if (!$quiz) {
    die('Quiz non trouvé');
}

$sqlQuestions = "SELECT * FROM question WHERE id_quiz = :id ORDER BY id_question ASC";
$queryQuestions = $db->prepare($sqlQuestions);
$queryQuestions->execute(['id' => $idQuiz]);
$questions = $queryQuestions->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($quiz['titre']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; }
        .wrapper { max-width: 900px; margin: 2rem auto; }
        .header-box, .question-box {
            background: white;
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .question-box h4 { margin-bottom: 1rem; }
        .choice {
            display: block;
            padding: .9rem 1rem;
            margin-bottom: .75rem;
            border: 1px solid #dbe3ee;
            border-radius: 12px;
            cursor: pointer;
            transition: .2s;
        }
        .choice:hover { background: #eff6ff; border-color: var(--primary); }
        .submit-box { text-align: center; margin-top: 2rem; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header-box">
        <h2><?= htmlspecialchars($quiz['titre']) ?></h2>
        <p><?= htmlspecialchars($quiz['description']) ?></p>
    </div>

    <form action="front-quiz-result.php" method="POST">
        <input type="hidden" name="id_quiz" value="<?= $idQuiz ?>">

        <?php if (!empty($questions)) { ?>
            <?php foreach ($questions as $index => $question) { ?>
                <div class="question-box">
                    <h4>Question <?= $index + 1 ?> : <?= htmlspecialchars($question['question']) ?></h4>

                    <label class="choice">
                        <input type="radio" name="reponse[<?= (int)$question['id_question'] ?>]" value="1">
                        <?= htmlspecialchars($question['choix1']) ?>
                    </label>

                    <label class="choice">
                        <input type="radio" name="reponse[<?= (int)$question['id_question'] ?>]" value="2">
                        <?= htmlspecialchars($question['choix2']) ?>
                    </label>

                    <label class="choice">
                        <input type="radio" name="reponse[<?= (int)$question['id_question'] ?>]" value="3">
                        <?= htmlspecialchars($question['choix3']) ?>
                    </label>

                    <label class="choice">
                        <input type="radio" name="reponse[<?= (int)$question['id_question'] ?>]" value="4">
                        <?= htmlspecialchars($question['choix4']) ?>
                    </label>
                </div>
            <?php } ?>

            <div class="submit-box">
                <button type="submit" class="btn btn-primary">Voir le résultat</button>
            </div>
        <?php } else { ?>
            <div class="question-box">
                <p>Aucune question n’a encore été ajoutée à ce quiz.</p>
            </div>
        <?php } ?>
    </form>
</div>
</body>
</html>
