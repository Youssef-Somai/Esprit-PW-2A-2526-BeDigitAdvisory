<?php

class Question
{
    private $id_question;
    private $question;
    private $choix1;
    private $choix2;
    private $choix3;
    private $bonne_reponse;
    private $point;
    private $id_quiz;

    // CONSTRUCTEUR
    public function __construct(
        $id_question = null,
        $question = "",
        $choix1 = "",
        $choix2 = "",
        $choix3 = "",
        $bonne_reponse = "",
        $point = 1,
        $id_quiz = null
    ) {
        $this->id_question = $id_question;
        $this->question = $question;
        $this->choix1 = $choix1;
        $this->choix2 = $choix2;
        $this->choix3 = $choix3;
        $this->bonne_reponse = $bonne_reponse;
        $this->point = $point;
        $this->id_quiz = $id_quiz;
    }

    // GETTERS
    public function getIdQuestion() {
        return $this->id_question;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getChoix1() {
        return $this->choix1;
    }

    public function getChoix2() {
        return $this->choix2;
    }

    public function getChoix3() {
        return $this->choix3;
    }

    public function getBonneReponse() {
        return $this->bonne_reponse;
    }

    public function getPoint() {
        return $this->point;
    }

    public function getIdQuiz() {
        return $this->id_quiz;
    }

    // SETTERS
    public function setQuestion($question) {
        $this->question = $question;
    }

    public function setChoix1($choix1) {
        $this->choix1 = $choix1;
    }

    public function setChoix2($choix2) {
        $this->choix2 = $choix2;
    }

    public function setChoix3($choix3) {
        $this->choix3 = $choix3;
    }

    public function setBonneReponse($bonne_reponse) {
        $this->bonne_reponse = $bonne_reponse;
    }

    public function setPoint($point) {
        $this->point = $point;
    }

    public function setIdQuiz($id_quiz) {
        $this->id_quiz = $id_quiz;
    }
}

?>