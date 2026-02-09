/**
 * Loading States - Indicateurs de chargement et skeleton loaders
 */

class LoadingStates {
    constructor() {
        this.overlay = null;
        this.init();
    }

    init() {
        this.createOverlay();
    }

    createOverlay() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'loading-overlay';
        this.overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-text">Chargement...</div>
            </div>
        `;
        document.body.appendChild(this.overlay);
    }

    show(message = 'Chargement...') {
        const text = this.overlay.querySelector('.spinner-text');
        if (text) text.textContent = message;
        this.overlay.classList.add('loading-overlay-show');
        document.body.style.overflow = 'hidden';
    }

    hide() {
        this.overlay.classList.remove('loading-overlay-show');
        document.body.style.overflow = '';
    }

    // Ajouter un loading state à un bouton spécifique
    buttonLoading(button, loading = true) {
        if (loading) {
            button.disabled = true;
            button.classList.add('btn-loading');
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<span class="spinner"></span> ${button.textContent}`;
        } else {
            button.disabled = false;
            button.classList.remove('btn-loading');
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
            }
        }
    }

    // Créer un skeleton loader pour une grille d'items
    createSkeletonGrid(container, count = 6) {
        container.innerHTML = '';
        container.classList.add('skeleton-container');

        for (let i = 0; i < count; i++) {
            const skeleton = document.createElement('div');
            skeleton.className = 'skeleton-card';
            skeleton.innerHTML = `
                <div class="skeleton-image"></div>
                <div class="skeleton-body">
                    <div class="skeleton-line skeleton-line-sm"></div>
                    <div class="skeleton-line skeleton-line-md"></div>
                    <div class="skeleton-line skeleton-line-lg"></div>
                </div>
            `;
            container.appendChild(skeleton);
        }
    }

    // Retirer les skeleton loaders
    removeSkeletons(container) {
        container.classList.remove('skeleton-container');
        const skeletons = container.querySelectorAll('.skeleton-card');
        skeletons.forEach(s => s.remove());
    }
}

// Instance globale
const Loading = new LoadingStates();

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Loading;
}
