document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('questionForm');
    if (!form) {
        return;
    }

    const question = document.getElementById('question');
    const choix1 = document.getElementById('choix1');
    const choix2 = document.getElementById('choix2');
    const choix3 = document.getElementById('choix3');
    const bonneReponse = document.getElementById('bonne_reponse');
    const point = document.getElementById('point');
    const idQuiz = document.getElementById('id_quiz');

    const params = new URLSearchParams(window.location.search);
    const quizId = params.get('id_quiz');

    if (quizId) {
        idQuiz.value = quizId;
        form.action = '/DigitAdvisory/Controller/QuestionController.php?action=add&id_quiz=' + quizId;
    } else {
        form.action = '/DigitAdvisory/Controller/QuestionController.php?action=add';
    }

    question.addEventListener('input', validateQuestion);
    choix1.addEventListener('input', validateChoix1);
    choix2.addEventListener('input', validateChoix2);
    choix3.addEventListener('input', validateChoix3);
    bonneReponse.addEventListener('input', validateBonneReponse);
    point.addEventListener('input', validatePoint);

    form.addEventListener('submit', function (e) {
        let valid = true;

        if (!validateQuestion()) valid = false;
        if (!validateChoix1()) valid = false;
        if (!validateChoix2()) valid = false;
        if (!validateChoix3()) valid = false;
        if (!validateBonneReponse()) valid = false;
        if (!validatePoint()) valid = false;

        if (idQuiz.value.trim() === '') {
            alert('ID du quiz introuvable.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });

    function showError(input, message) {
        const formGroup = input.parentElement;
        formGroup.classList.remove('success');
        formGroup.classList.add('error');

        let errorMsg = formGroup.querySelector('.error-message');
        if (!errorMsg) {
            errorMsg = document.createElement('small');
            errorMsg.className = 'error-message';
            formGroup.appendChild(errorMsg);
        }

        errorMsg.textContent = message;
    }

    function showSuccess(input) {
        const formGroup = input.parentElement;
        formGroup.classList.remove('error');
        formGroup.classList.add('success');

        const errorMsg = formGroup.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    function validateQuestion() {
        const value = question.value.trim();

        if (value === '') {
            showError(question, 'La question est obligatoire.');
            return false;
        }

        if (value.length < 5) {
            showError(question, 'La question doit contenir au moins 5 caractères.');
            return false;
        }

        showSuccess(question);
        return true;
    }

    function validateChoix1() {
        const value = choix1.value.trim();

        if (value === '') {
            showError(choix1, 'Le choix 1 est obligatoire.');
            return false;
        }

        showSuccess(choix1);
        return true;
    }

    function validateChoix2() {
        const value = choix2.value.trim();

        if (value === '') {
            showError(choix2, 'Le choix 2 est obligatoire.');
            return false;
        }

        showSuccess(choix2);
        return true;
    }

    function validateChoix3() {
        const value = choix3.value.trim();

        if (value === '') {
            showError(choix3, 'Le choix 3 est obligatoire.');
            return false;
        }

        showSuccess(choix3);
        return true;
    }

    function validateBonneReponse() {
        const value = bonneReponse.value.trim();
        const c1 = choix1.value.trim();
        const c2 = choix2.value.trim();
        const c3 = choix3.value.trim();

        if (value === '') {
            showError(bonneReponse, 'La bonne réponse est obligatoire.');
            return false;
        }

        if (value !== c1 && value !== c2 && value !== c3) {
            showError(bonneReponse, 'La bonne réponse doit être égale à un des trois choix.');
            return false;
        }

        showSuccess(bonneReponse);
        return true;
    }

    function validatePoint() {
        const value = point.value.trim();

        if (value === '') {
            showError(point, 'Le point est obligatoire.');
            return false;
        }

        if (parseInt(value, 10) <= 0) {
            showError(point, 'Le point doit être supérieur à 0.');
            return false;
        }

        showSuccess(point);
        return true;
    }
});