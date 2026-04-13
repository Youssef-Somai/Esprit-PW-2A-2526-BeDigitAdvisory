<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Quiz.php';

class QuizController
{
    // =========================
    // AFFICHER FORMULAIRE AJOUT
    // =========================
    public function create()
    {
        include __DIR__ . '/../View/BackOffice/create.html';
    }

    // =========================
    // AJOUTER QUIZ
    // =========================
    public function addQuiz($quiz)
    {
        $sql = "INSERT INTO quiz (titre, description, image, date_creation)
                VALUES (:titre, :description, :image, :date_creation)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $quiz->getTitre());
            $query->bindValue(':description', $quiz->getDescription());
            $query->bindValue(':image', $quiz->getImage());
            $query->bindValue(':date_creation', $quiz->getDateCreation());
            $query->execute();

            header('Location: /DigitAdvisory/Controller/QuizController.php?action=read');
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() ;
        }
    }

    // =========================
    // LISTER TOUS LES QUIZ
    // =========================
    public function listQuiz()
    {
        $sql = "SELECT * FROM quiz ";
        $db = config::getConnexion();

        try {
            $liste = $db->query($sql);
            $quizzes = $liste->fetchAll();

            include __DIR__ . '/../View/BackOffice/read.html';
        } catch (Exception $e) {
           echo 'Error: ' . $e->getMessage() ;
        }
    }

    // =========================
    // AFFICHER UN SEUL QUIZ
    // =========================
    public function showQuiz($id)
    {
        $sql = "SELECT * FROM quiz WHERE id_quiz = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();

            $quiz = $query->fetch();

            return $quiz;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() ;
        }
    }

    // =========================
    // AFFICHER FORMULAIRE MODIFIER
    // =========================
    public function editQuiz($id)
    {
        $quiz = $this->showQuiz($id);
        include __DIR__ . '/../View/BackOffice/update.html';
    }

    // =========================
    // MODIFIER QUIZ
    // =========================
    public function updateQuiz($quiz, $id)
    {
        $sql = "UPDATE quiz 
                SET titre = :titre,
                    description = :description,
                    image = :image,
                    date_creation = :date_creation
                WHERE id_quiz = :id";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->bindValue(':titre', $quiz->getTitre());
            $query->bindValue(':description', $quiz->getDescription());
            $query->bindValue(':image', $quiz->getImage());
            $query->bindValue(':date_creation', $quiz->getDateCreation());
            $query->execute();

            header('Location: /DigitAdvisory/Controller/QuizController.php?action=read');
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage() ;
        }
    }

    // =========================
    // SUPPRIMER QUIZ
    // =========================
    public function deleteQuiz($id)
    {
        $db = config::getConnexion();

        try {
            // supprimer image
            $sql = "SELECT image FROM quiz WHERE id_quiz = :id";
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            $quiz = $query->fetch();

            if ($quiz && !empty($quiz['image'])) {
                $imagePath = __DIR__ . '/../public/uploads/' . $quiz['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // supprimer ligne
            $sql2 = "DELETE FROM quiz WHERE id_quiz = :id";
            $query2 = $db->prepare($sql2);
            $query2->bindValue(':id', $id);
            $query2->execute();

            header('Location: /DigitAdvisory/Controller/QuizController.php?action=read');
            exit();
        } catch (Exception $e) {
           echo 'Error: ' . $e->getMessage() ;
        }
    }
}

// =========================
// TRAITEMENT
// =========================


$quizC = new QuizController();

$action = $_GET['action'] ?? 'read';

if ($action == 'read') {
    $quizC->listQuiz();
}

elseif ($action == 'create') {
    $quizC->create();
}

elseif ($action == 'edit' && isset($_GET['id'])) {
    $quizC->editQuiz($_GET['id']);
}

elseif ($action == 'delete' && isset($_GET['id'])) {
    $quizC->deleteQuiz($_GET['id']);
}

elseif ($action == 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date = $_POST['date_creation'];

    $fileName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    $newImageName = uniqid('quiz_', true) . '.' . $extension;
    $uploadDir = __DIR__ . '/../public/uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    move_uploaded_file($tmpName, $uploadDir . $newImageName);

    $dateCreation = str_replace('T', ' ', $date) . ':00';

    $quiz = new Quiz(null, $titre, $description, $newImageName, $dateCreation);
    $quizC->addQuiz($quiz);
}

elseif ($action == 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['id'];
    $oldQuiz = $quizC->showQuiz($id);

    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date = $_POST['date_creation'];

    $dateCreation = str_replace('T', ' ', $date) . ':00';

    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $fileName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $newImageName = uniqid('quiz_', true) . '.' . $extension;
        $uploadDir = __DIR__ . '/../public/uploads/';



        move_uploaded_file($tmpName, $uploadDir . $newImageName);

        if ($oldQuiz && !empty($oldQuiz['image'])) {
            $oldImagePath = __DIR__ . '/../public/uploads/' . $oldQuiz['image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    } else {
        $newImageName = $oldQuiz['image'];
    }

    $quiz = new Quiz($id, $titre, $description, $newImageName, $dateCreation);
    $quizC->updateQuiz($quiz, $id);
}

else {
    $quizC->listQuiz();
}