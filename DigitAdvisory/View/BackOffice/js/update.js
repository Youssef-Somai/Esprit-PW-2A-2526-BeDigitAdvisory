document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('quizForm');
    const titre = document.getElementById('titre');
    const description = document.getElementById('description');
    const image = document.getElementById('image');
    const dateCreation = document.getElementById('date_creation');

    titre.addEventListener('input', validateTitre);
    description.addEventListener('input', validateDescription);
    image.addEventListener('change', validateImage);
    dateCreation.addEventListener('change', validateDate);

    form.addEventListener('submit', function (e) {
        let valid = true;

        if (!validateTitre()) valid = false;
        if (!validateDescription()) valid = false;
        if (!validateImage()) valid = false;
        if (!validateDate()) valid = false;

        if (!valid) {
            e.preventDefault();
        }
    });

    function showError(input, message) {
        const group = input.parentElement;
        group.classList.add('error');
        group.classList.remove('success');

        let msg = group.querySelector('.error-message');
        if (!msg) {
            msg = document.createElement('small');
            msg.className = 'error-message';
            group.appendChild(msg);
        }

        msg.textContent = message;
    }

    function showSuccess(input) {
        const group = input.parentElement;
        group.classList.remove('error');
        group.classList.add('success');

        const msg = group.querySelector('.error-message');
        if (msg) msg.remove();
    }

    function validateTitre() {
        const value = titre.value.trim();

        if (value === '') {
            showError(titre, 'Titre obligatoire');
            return false;
        }

        if (value.length < 3) {
            showError(titre, 'Minimum 3 caractères');
            return false;
        }

        showSuccess(titre);
        return true;
    }

    function validateDescription() {
        const value = description.value.trim();

        if (value === '') {
            showError(description, 'Description obligatoire');
            return false;
        }

        if (value.length < 10) {
            showError(description, 'Minimum 10 caractères');
            return false;
        }

        showSuccess(description);
        return true;
    }

    function validateImage() {
        if (image.files.length === 0) {
            return true; // ✔️ optionnel
        }

        const file = image.files[0];
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 2 * 1024 * 1024;

        if (!allowed.includes(file.type)) {
            showError(image, 'Format invalide');
            return false;
        }

        if (file.size > maxSize) {
            showError(image, 'Max 2MB');
            return false;
        }

        showSuccess(image);
        return true;
    }

    function validateDate() {
        if (dateCreation.value.trim() === '') {
            showError(dateCreation, 'Date obligatoire');
            return false;
        }

        showSuccess(dateCreation);
        return true;
    }
});