/**
 * Accessibility Helpers - Amélioration de l'accessibilité
 */

class AccessibilityHelpers {
    constructor() {
        this.init();
    }

    init() {
        // Gestion du focus trap dans les modals
        this.setupFocusTrap();

        // Annonces pour lecteurs d'écran
        this.createLiveRegion();

        // Navigation au clavier améliorée
        this.enhanceKeyboardNav();

        // Détection de préférence de mouvement réduit
        this.detectReducedMotion();
    }

    // Créer une région live pour les annonces ARIA
    createLiveRegion() {
        if (!document.getElementById('aria-live-region')) {
            const liveRegion = document.createElement('div');
            liveRegion.id = 'aria-live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            document.body.appendChild(liveRegion);
        }
    }

    // Annoncer un message aux lecteurs d'écran
    announce(message, priority = 'polite') {
        const liveRegion = document.getElementById('aria-live-region');
        if (liveRegion) {
            liveRegion.setAttribute('aria-live', priority);
            liveRegion.textContent = message;

            // Nettoyer après 1 seconde
            setTimeout(() => {
                liveRegion.textContent = '';
            }, 1000);
        }
    }

    // Focus trap pour les modals
    setupFocusTrap() {
        document.addEventListener('keydown', (e) => {
            const modal = document.querySelector('.modal.active, [role="dialog"][aria-hidden="false"]');
            if (!modal) return;

            const focusableElements = modal.querySelectorAll(
                'a[href], button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );

            if (focusableElements.length === 0) return;

            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            // Tab key
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    // Shift + Tab
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    // Tab
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }

            // Escape key pour fermer
            if (e.key === 'Escape') {
                const closeBtn = modal.querySelector('[data-dismiss], .modal-close, .close');
                if (closeBtn) closeBtn.click();
            }
        });
    }

    // Navigation au clavier améliorée
    enhanceKeyboardNav() {
        // Skip links
        const skipLinks = document.querySelectorAll('.skip-link');
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                    target.addEventListener('blur', () => {
                        target.removeAttribute('tabindex');
                    }, { once: true });
                }
            });
        });

        // Navigation dans les carousels avec flèches
        const carousels = document.querySelectorAll('[role="region"][aria-label*="carousel"], .carousel-track');
        carousels.forEach(carousel => {
            carousel.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    const prevBtn = document.querySelector('.carousel-prev');
                    if (prevBtn) prevBtn.click();
                } else if (e.key === 'ArrowRight') {
                    const nextBtn = document.querySelector('.carousel-next');
                    if (nextBtn) nextBtn.click();
                }
            });
        });

        // Améliorer les boutons hamburger
        const hamburgers = document.querySelectorAll('.hamburger, [aria-label*="Menu"]');
        hamburgers.forEach(btn => {
            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !expanded);
            });
        });
    }

    // Détecter la préférence de mouvement réduit
    detectReducedMotion() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

        if (prefersReducedMotion.matches) {
            document.documentElement.classList.add('reduce-motion');
            this.announce('Animations réduites activées');
        }

        // Écouter les changements
        prefersReducedMotion.addEventListener('change', (e) => {
            if (e.matches) {
                document.documentElement.classList.add('reduce-motion');
                this.announce('Animations réduites activées');
            } else {
                document.documentElement.classList.remove('reduce-motion');
            }
        });
    }

    // Améliorer les tooltips pour l'accessibilité
    enhanceTooltips() {
        const tooltipElements = document.querySelectorAll('[title], [data-tooltip]');

        tooltipElements.forEach(el => {
            const tooltipText = el.getAttribute('title') || el.getAttribute('data-tooltip');
            if (!tooltipText) return;

            // Retirer le title natif et le remplacer par aria-label
            el.removeAttribute('title');
            el.setAttribute('aria-label', tooltipText);

            // Créer un tooltip personnalisé
            const tooltip = document.createElement('span');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = tooltipText;
            tooltip.setAttribute('role', 'tooltip');

            el.style.position = 'relative';
            el.appendChild(tooltip);

            // Afficher au hover et au focus
            el.addEventListener('mouseenter', () => tooltip.classList.add('show'));
            el.addEventListener('mouseleave', () => tooltip.classList.remove('show'));
            el.addEventListener('focus', () => tooltip.classList.add('show'));
            el.addEventListener('blur', () => tooltip.classList.remove('show'));
        });
    }
}

// Classe pour masquer visuellement mais garder accessible
const srOnlyStyle = document.createElement('style');
srOnlyStyle.textContent = `
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    
    .custom-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-8px);
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
        pointer-events: none;
        z-index: 1000;
    }
    
    .custom-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.9);
    }
    
    .custom-tooltip.show {
        opacity: 1;
        visibility: visible;
    }
`;
document.head.appendChild(srOnlyStyle);

// Instance globale
const A11y = new AccessibilityHelpers();

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = A11y;
}
