document.addEventListener('DOMContentLoaded', function() {
    console.log(getDataVariable('variable_success_or_no'))

    function getDataVariable(id) {
        return document.getElementById(id).getAttribute('data-variable');
    }

    function toggleDisplay(className, displayStyle) {
        const element = document.getElementsByClassName(className)[0];
        if (element) {
            element.style.display = displayStyle;
        }else{
            console.log('Element not found');
        }
    }



    setTimeout(function() {
        const variableJs = getDataVariable('variable_success_or_no');
        if (variableJs === 'success') {
            toggleDisplay('inscription', 'none');
            toggleDisplay('input_display', 'none');
            toggleDisplay('dot-flashing', 'block');
        }
    }, 100);


    setTimeout(function() {
        const variableJs = getDataVariable('variable_success_or_no');
        if (variableJs === 'success') {
            console.log('success');
            window.location.href = "index.php";
        }
    }, 2700);

    document.getElementById("inscription").addEventListener("click", function() {
        toggleDisplay("connexion", "none");
        toggleDisplay("noAccount", "none");
        toggleDisplay("inscription", "block");
    });

    document.getElementsByClassName("arrow_return")[0].addEventListener("click", function() {
        toggleDisplay("connexion", "block");
        toggleDisplay("noAccount", "flex");
        toggleDisplay("inscription", "none");
    });

    if(getDataVariable('variable_success_or_no') === 'inscription'){
        toggleDisplay('inscription', 'none');
        toggleDisplay('connexion', 'none');
        toggleDisplay('noAccount', 'none');
        toggleDisplay('code_inscription', 'block');
    }

    if( getDataVariable('stat_code') !== 'false'){
        toggleDisplay('inscription', 'none');
        toggleDisplay('connexion', 'none');
        toggleDisplay('noAccount', 'none');
        toggleDisplay('code_inscription', 'block');
    }

    const inputs = document.querySelectorAll('.digit-input');
    const form = document.getElementById('digitForm');

    const checkInputs = () => {
        const allFilled = Array.from(inputs).every(input => /^[0-9]$/.test(input.value));
        const expirationDate = new Date(sessionStorage.getItem('auth_expiration_date'));
        const maintenant = new Date();

        const digitForm = document.getElementById('digitForm');

        if (maintenant > expirationDate) {
            console.log('Le code a expiré');
        } else {
            if (allFilled) {
                digitForm.submit();
            }

        }
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1) {
                const nextInput = inputs[index + 1];
                if (nextInput) {
                    nextInput.focus();
                }
            }
            checkInputs();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value.length === 0) {
                const prevInput = inputs[index - 1];
                if (prevInput) {
                    prevInput.focus();
                }
            }
            checkInputs();
        });
    });

});
