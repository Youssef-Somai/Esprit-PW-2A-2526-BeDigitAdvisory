document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('quizForm');
    const titre = document.getElementById('titre');
    const description = document.getElementById('description');
    const image = document.getElementById('image');
    const dateCreation = document.getElementById('date_creation');
    const successBox = document.getElementById('successBox');

    const params = new URLSearchParams(window.location.search);
    if (successBox !== null && params.get('success') === '1') {
        successBox.style.display = 'block';
    }

    titre.addEventListener('input', validateTitre);
    description.addEventListener('input', validateDescription);
    image.addEventListener('change', validateImage);
    dateCreation.addEventListener('change', validateDate);

    form.addEventListener('submit', function (e) {
        let valid = true;

        if (!validateTitre()) {
            valid = false;
        }

        if (!validateDescription()) {
            valid = false;
        }

        if (!validateImage()) {
            valid = false;
        }

        if (!validateDate()) {
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

        if (errorMsg === null) {
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
        if (errorMsg !== null) {
            errorMsg.remove();
        }
    }

    function validateTitre() {
        const value = titre.value.trim();

        if (value === '') {
            showError(titre, 'Le titre est obligatoire.');
            return false;
        }

        if (value.length < 3) {
            showError(titre, 'Le titre doit contenir au moins 3 caractères.');
            return false;
        }

        if (value.length > 150) {
            showError(titre, 'Le titre ne doit pas dépasser 150 caractères.');
            return false;
        }

        showSuccess(titre);
        return true;
    }

    function validateDescription() {
        const value = description.value.trim();

        if (value === '') {
            showError(description, 'La description est obligatoire.');
            return false;
        }

        if (value.length < 10) {
            showError(description, 'La description doit contenir au moins 10 caractères.');
            return false;
        }

        showSuccess(description);
        return true;
    }

    function validateImage() {
        if (image.files.length === 0) {
            showError(image, 'Veuillez choisir une image.');
            return false;
        }

        const file = image.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 2 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            showError(image, 'Format non valide. Utilisez JPG, PNG ou WEBP.');
            return false;
        }

        if (file.size > maxSize) {
            showError(image, 'La taille maximale est de 2 Mo.');
            return false;
        }

        showSuccess(image);
        return true;
    }

    function validateDate() {
        const value = dateCreation.value.trim();

        if (value === '') {
            showError(dateCreation, 'La date de création est obligatoire.');
            return false;
        }

        showSuccess(dateCreation);
        return true;
    }
     alert("JS chargé");
   console.log('JS chargé');
   
});