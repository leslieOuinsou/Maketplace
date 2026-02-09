/**
 * Enhanced Forms - Validation en temps réel et améliorations UX
 */

class EnhancedForms {
    constructor() {
        this.init();
    }

    init() {
        // Validation en temps réel
        this.setupRealtimeValidation();

        // Prévisualisation d'images
        this.setupImagePreview();

        // Indicateur de force de mot de passe
        this.setupPasswordStrength();

        // Auto-formatage des champs
        this.setupAutoFormatting();

        // Compteur de caractères
        this.setupCharacterCounter();

        // Loading states pour les formulaires
        this.setupFormSubmission();
    }

    setupRealtimeValidation() {
        const inputs = document.querySelectorAll('input[required], textarea[required], select[required]');

        inputs.forEach(input => {
            // Validation au blur
            input.addEventListener('blur', () => this.validateField(input));

            // Validation pendant la saisie (debounced)
            let timeout;
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => this.validateField(input), 500);
            });
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let errorMessage = '';

        // Vérifier si requis
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Ce champ est requis';
        }
        // Validation email
        else if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Email invalide';
            }
        }
        // Validation nombre
        else if (type === 'number' && value) {
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');
            const numValue = parseFloat(value);

            if (min && numValue < parseFloat(min)) {
                isValid = false;
                errorMessage = `Minimum: ${min}`;
            }
            if (max && numValue > parseFloat(max)) {
                isValid = false;
                errorMessage = `Maximum: ${max}`;
            }
        }

        this.showFieldFeedback(field, isValid, errorMessage);
        return isValid;
    }

    showFieldFeedback(field, isValid, message) {
        // Retirer les anciens messages
        const existingError = field.parentElement.querySelector('.field-error');
        if (existingError) existingError.remove();

        // Retirer les classes
        field.classList.remove('field-valid', 'field-invalid');

        if (!isValid && message) {
            field.classList.add('field-invalid');

            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = message;
            field.parentElement.appendChild(errorDiv);

            // Animation shake
            field.classList.add('shake');
            setTimeout(() => field.classList.remove('shake'), 500);
        } else if (isValid && field.value.trim()) {
            field.classList.add('field-valid');
        }
    }

    setupImagePreview() {
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');

        imageInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                // Vérifier la taille (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Toast.error('Image trop volumineuse (max 5MB)');
                    input.value = '';
                    return;
                }

                // Créer la prévisualisation
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.showImagePreview(input, event.target.result, file.name);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    showImagePreview(input, src, filename) {
        // Retirer l'ancienne preview
        const existingPreview = input.parentElement.querySelector('.image-preview');
        if (existingPreview) existingPreview.remove();

        const preview = document.createElement('div');
        preview.className = 'image-preview';
        preview.innerHTML = `
            <img src="${src}" alt="Prévisualisation">
            <div class="image-preview-info">
                <span class="image-preview-name">${filename}</span>
                <button type="button" class="image-preview-remove" aria-label="Supprimer">✕</button>
            </div>
        `;

        input.parentElement.appendChild(preview);

        // Bouton de suppression
        preview.querySelector('.image-preview-remove').addEventListener('click', () => {
            preview.remove();
            input.value = '';
        });
    }

    setupPasswordStrength() {
        const passwordInputs = document.querySelectorAll('input[type="password"]');

        passwordInputs.forEach(input => {
            // Créer l'indicateur de force
            const strengthDiv = document.createElement('div');
            strengthDiv.className = 'password-strength';
            strengthDiv.innerHTML = `
                <div class="password-strength-bar">
                    <div class="password-strength-fill"></div>
                </div>
                <span class="password-strength-text"></span>
            `;
            input.parentElement.appendChild(strengthDiv);

            // Bouton toggle visibility
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'password-toggle';
            toggleBtn.innerHTML = '👁';
            toggleBtn.setAttribute('aria-label', 'Afficher le mot de passe');
            input.parentElement.style.position = 'relative';
            input.parentElement.appendChild(toggleBtn);

            toggleBtn.addEventListener('click', () => {
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                toggleBtn.innerHTML = type === 'password' ? '👁' : '🙈';
            });

            // Calculer la force
            input.addEventListener('input', () => {
                const strength = this.calculatePasswordStrength(input.value);
                this.updatePasswordStrength(strengthDiv, strength);
            });
        });
    }

    calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;

        return Math.min(strength, 4);
    }

    updatePasswordStrength(container, strength) {
        const fill = container.querySelector('.password-strength-fill');
        const text = container.querySelector('.password-strength-text');

        const levels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
        const colors = ['#dc2626', '#f59e0b', '#eab308', '#22c55e', '#16a34a'];

        fill.style.width = `${(strength / 4) * 100}%`;
        fill.style.backgroundColor = colors[strength];
        text.textContent = levels[strength];
        text.style.color = colors[strength];
    }

    setupAutoFormatting() {
        // Formatage des prix
        const priceInputs = document.querySelectorAll('input[name="price"], input[type="number"][step="0.01"]');
        priceInputs.forEach(input => {
            input.addEventListener('blur', () => {
                if (input.value) {
                    const value = parseFloat(input.value);
                    if (!isNaN(value)) {
                        input.value = value.toFixed(2);
                    }
                }
            });
        });
    }

    setupCharacterCounter() {
        const textareas = document.querySelectorAll('textarea[maxlength]');

        textareas.forEach(textarea => {
            const maxLength = textarea.getAttribute('maxlength');

            const counter = document.createElement('div');
            counter.className = 'character-counter';
            textarea.parentElement.appendChild(counter);

            const updateCounter = () => {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${textarea.value.length} / ${maxLength}`;
                counter.classList.toggle('counter-warning', remaining < 50);
            };

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        });
    }

    setupFormSubmission() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn && !submitBtn.disabled) {
                    // Ajouter loading state
                    submitBtn.disabled = true;
                    submitBtn.classList.add('btn-loading');

                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = `<span class="spinner"></span> ${originalText}`;

                    // Si le formulaire est invalide, restaurer le bouton
                    setTimeout(() => {
                        if (!form.checkValidity()) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('btn-loading');
                            submitBtn.textContent = originalText;
                        }
                    }, 100);
                }
            });
        });
    }
}

// Initialiser au chargement de la page
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new EnhancedForms());
} else {
    new EnhancedForms();
}
