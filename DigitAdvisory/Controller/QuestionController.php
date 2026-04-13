<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Question.php';

class QuestionController
{
    public function create()
    {
        include __DIR__ . '/../View/BackOffice/question_create.html';
    }

    public function addQuestion($question)
    {
        $sql = "INSERT INTO question 
                (question, choix1, choix2, choix3, bonne_reponse, point, id_quiz)
                VALUES
                (:question, :choix1, :choix2, :choix3, :bonne_reponse, :point, :id_quiz)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':question', $question->getQuestion());
            $query->bindValue(':choix1', $question->getChoix1());
            $query->bindValue(':choix2', $question->getChoix2());
            $query->bindValue(':choix3', $question->getChoix3());
            $query->bindValue(':bonne_reponse', $question->getBonneReponse());
            $query->bindValue(':point', $question->getPoint());
            $query->bindValue(':id_quiz', $question->getIdQuiz());
            $query->execute();

            header('Location: /DigitAdvisory/Controller/QuestionController.php?action=read');
            exit();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listQuestion()
    {
        $sql = "SELECT * FROM question ORDER BY id_question DESC";
        $db = config::getConnexion();

        try {
            $liste = $db->query($sql);
            $questions = $liste->fetchAll();

            include __DIR__ . '/../View/BackOffice/question_read.html';
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function listQuestionByQuiz($idQuiz)
    {
        $sql = "SELECT * FROM question WHERE id_quiz = :id_quiz ORDER BY id_question DESC";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_quiz', $idQuiz);
            $query->execute();

            $questions = $query->fetchAll();

            include __DIR__ . '/../View/BackOffice/question_read.html';
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function showQuestion($id)
    {
        $sql = "SELECT * FROM question WHERE id_question = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function editQuestion($id)
    {
        $question = $this->showQuestion($id);
        include __DIR__ . '/../View/BackOffice/question_update.html';
    }

    public function updateQuestion($question, $id)
    {
        $sql = "UPDATE question
                SET question = :question,
                    choix1 = :choix1,
                    choix2 = :choix2,
                    choix3 = :choix3,
                    bonne_reponse = :bonne_reponse,
                    point = :point,
                    id_quiz = :id_quiz
                WHERE id_question = :id";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->bindValue(':question', $question->getQuestion());
            $query->bindValue(':choix1', $question->getChoix1());
            $query->bindValue(':choix2', $question->getChoix2());
            $query->bindValue(':choix3', $question->getChoix3());
            $query->bindValue(':bonne_reponse', $question->getBonneReponse());
            $query->bindValue(':point', $question->getPoint());
            $query->bindValue(':id_quiz', $question->getIdQuiz());
            $query->execute();

            header('Location: /DigitAdvisory/Controller/QuestionController.php?action=read');
            exit();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    public function deleteQuestion($id)
    {
        $sql = "DELETE FROM question WHERE id_question = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            header('Location: /DigitAdvisory/Controller/QuestionController.php?action=read');
            exit();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}

$questionC = new QuestionController();

$action = $_GET['action'] ?? 'read';

if ($action == 'read') {
    $questionC->listQuestion();
}

elseif ($action == 'readByQuiz' && isset($_GET['id_quiz'])) {
    $questionC->listQuestionByQuiz($_GET['id_quiz']);
}

elseif ($action == 'create') {
    $questionC->create();
}

elseif ($action == 'edit' && isset($_GET['id'])) {
    $questionC->editQuestion($_GET['id']);
}

elseif ($action == 'delete' && isset($_GET['id'])) {
    $questionC->deleteQuestion($_GET['id']);
}

elseif ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $questionTexte = $_POST['question'] ?? '';
    $choix1 = $_POST['choix1'] ?? '';
    $choix2 = $_POST['choix2'] ?? '';
    $choix3 = $_POST['choix3'] ?? '';
    $bonneReponse = $_POST['bonne_reponse'] ?? '';
    $point = $_POST['point'] ?? 1;
    $idQuiz = $_POST['id_quiz'] ?? ($_GET['id_quiz'] ?? null);

    $question = new Question(
        null,
        $questionTexte,
        $choix1,
        $choix2,
        $choix3,
        $bonneReponse,
        $point,
        $idQuiz
    );

    $questionC->addQuestion($question);
}

elseif ($action == 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'];

    $questionTexte = $_POST['question'] ?? '';
    $choix1 = $_POST['choix1'] ?? '';
    $choix2 = $_POST['choix2'] ?? '';
    $choix3 = $_POST['choix3'] ?? '';
    $bonneReponse = $_POST['bonne_reponse'] ?? '';
    $point = $_POST['point'] ?? 1;
    $idQuiz = $_POST['id_quiz'] ?? null;

    $question = new Question(
        $id,
        $questionTexte,
        $choix1,
        $choix2,
        $choix3,
        $bonneReponse,
        $point,
        $idQuiz
    );

    $questionC->updateQuestion($question, $id);
}

else {
    $questionC->listQuestion();
}
?>