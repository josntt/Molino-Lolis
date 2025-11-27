document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registro-form');
    
    // COMENTARIO NUEVO: Se añade esta verificación.
    // Si el formulario no existe en esta página, no se ejecuta el script.
    if (!form) {
        return;
    }

    const steps = Array.from(form.querySelectorAll('.form-step'));
    const btnNext = document.getElementById('btn-next');
    const btnPrev = document.getElementById('btn-prev');
    const progressSteps = Array.from(form.querySelectorAll('.progress-step'));
    
    let currentStep = 0;

    // Muestra el paso actual y oculta los demás
    const showStep = (stepIndex) => {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });
        
        // Actualiza la barra de progreso
        progressSteps.forEach((step, index) => {
            step.classList.toggle('active', index <= stepIndex);
        });
        
        // Actualiza los botones
        btnPrev.style.display = stepIndex === 0 ? 'none' : 'inline-block';
        btnNext.textContent = stepIndex === steps.length - 1 ? 'Registrar' : 'Siguiente';
    };

    // --- Validación ---
    const showError = (input, message) => {
        const formGroup = input.parentElement;
        const errorField = formGroup.querySelector('.error-message');
        input.classList.add('error');
        errorField.textContent = message;
    };

    const clearError = (input) => {
        const formGroup = input.parentElement;
        const errorField = formGroup.querySelector('.error-message');
        input.classList.remove('error');
        errorField.textContent = '';
    };

    const validateEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    };

    
    const validateName = (name) => {
        const re = /^[\p{L} ]+$/u;
        return re.test(String(name));
    };

    
    const validatePhone = (phone) => {
        const re = /^[0-9]{10}$/;
        return re.test(String(phone));
    };


    const validateStep = (stepIndex) => {
        let isValid = true;
        const currentStepFields = steps[stepIndex].querySelectorAll('input[required], select[required]');
        
        currentStepFields.forEach(input => {
            clearError(input); // Limpia errores anteriores
            
            // Validación de campo vacío (para todos)
            if (input.value.trim() === '') {
                isValid = false;
                showError(input, 'Este campo es obligatorio.');
            } 
            
            // Validación de formato para Nombre y Apellidos
            else if ((input.id === 'nombre' || input.id === 'apellidos') && !validateName(input.value)) {
                isValid = false;
                showError(input, 'Solo se permiten letras y espacios.');
            }
            
            // Validación de formato para Teléfono
            else if (input.id === 'telefono' && !validatePhone(input.value)) {
                isValid = false;
                showError(input, 'Debe ser un número de 10 dígitos.');
            }
            
            // Validación de formato para Correo (ya existía)
            else if (input.id === 'correo' && !validateEmail(input.value)) {
                isValid = false;
                showError(input, 'Por favor, ingresa un correo válido.');
            } 
            
            // Validación de formato para Contraseña (ya existía)
            else if (input.id === 'contrasena' && input.value.length < 8) {
                isValid = false;
                showError(input, 'La contraseña debe tener al menos 8 caracteres.');
            } 
            
            else if (input.id === 'confirmar_contrasena') { 
                const pass = document.getElementById('contrasena').value;
                if (input.value !== pass) {
                    isValid = false;
                    showError(input, 'Las contraseñas no coinciden.');
                }
            }
        });
        return isValid;
    };

    // --- Event Listeners ---
    btnNext.addEventListener('click', () => {

        if (!validateStep(currentStep)) {
            return; // Detiene si la validación falla
        }
        
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        } else {
            // Si está en el último paso, envía el formulario
            form.submit();
        }
    });

    btnPrev.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Limpia errores al escribir
    form.querySelectorAll('input[required], select[required]').forEach(input => {
        input.addEventListener('input', () => clearError(input));
    });

    // Mostrar el primer paso al cargar
    showStep(currentStep);
});