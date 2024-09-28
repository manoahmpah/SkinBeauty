class Upload_form {

    constructor() {
        this.initEventListeners();
    }

    // Méthode pour vérifier si tous les inputs et textareas sont remplis
    inputVerified() {
        const inputs = document.querySelectorAll('input');
        const textareas = document.querySelectorAll('textarea');

        // Vérifier les champs input
        for (let input of inputs) {
            if (input.value.trim() === "") {
                return false; // Un champ input est vide
            }
        }

        // Vérifier les champs textarea
        for (let textarea of textareas) {
            if (textarea.value.trim() === "") {
                return false;
            }
        }

        return true;
    }

    initEventListeners() {
        const inputs = document.querySelectorAll('input');
        const textareas = document.querySelectorAll('textarea');

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.inputVerified() ? this.enableSubmitButton() : this.disableSubmitButton();
            });
        });

        textareas.forEach(textarea => {
            textarea.addEventListener('input', () => {
                this.inputVerified() ? this.enableSubmitButton() : this.disableSubmitButton();
            });
        });

        // Écouteur d'événement pour le bouton "Ajouter catégorie"
        const form = document.getElementById('form-services');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            this.addCategory();
        });
    }

    enableSubmitButton() {
        const submitButton = document.getElementById('add-category');
        submitButton.removeAttribute('disabled');
    }

    disableSubmitButton() {
        const submitButton = document.getElementById('add-category');
        submitButton.setAttribute('disabled', 'disabled');
    }

    addCategory() {
        const form = document.getElementById('form-services');
        const newLabel = document.createElement('label');
        const newInput = document.createElement('input');
        newInput.placeholder = 'Nom de la catégorie';
        newInput.name = 'categories[]';
        newInput.type = 'text';
        newInput.required = true;
        newLabel.appendChild(newInput);
        form.appendChild(newLabel);

    }
}

new Upload_form();
