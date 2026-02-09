/**
 * Image Optimizer - Lazy loading et optimisation des images
 */

class ImageOptimizer {
    constructor() {
        this.init();
    }

    init() {
        // Lazy loading avec Intersection Observer
        this.setupLazyLoading();

        // Compression avant upload
        this.setupImageCompression();
    }

    setupLazyLoading() {
        // Vérifier le support d'Intersection Observer
        if (!('IntersectionObserver' in window)) {
            // Fallback: charger toutes les images
            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
            return;
        }

        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    this.loadImage(img);
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px' // Charger 50px avant que l'image soit visible
        });

        // Observer toutes les images lazy
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    loadImage(img) {
        const src = img.dataset.src || img.src;

        // Créer une image temporaire pour le chargement
        const tempImg = new Image();
        tempImg.onload = () => {
            img.src = src;
            img.classList.add('loaded');
        };
        tempImg.onerror = () => {
            img.classList.add('error');
            // Image de fallback
            img.src = '/images/placeholder.png';
        };
        tempImg.src = src;
    }

    setupImageCompression() {
        // Compression côté client avant upload
        const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');

        fileInputs.forEach(input => {
            input.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                if (!file || !file.type.startsWith('image/')) return;

                // Si l'image est trop grande, la compresser
                if (file.size > 1024 * 1024) { // > 1MB
                    try {
                        const compressed = await this.compressImage(file);

                        // Créer un nouveau File object
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressed);
                        input.files = dataTransfer.files;

                        Toast.info(`Image compressée: ${(file.size / 1024).toFixed(0)}KB → ${(compressed.size / 1024).toFixed(0)}KB`);
                    } catch (error) {
                        console.error('Erreur de compression:', error);
                    }
                }
            });
        });
    }

    async compressImage(file, maxWidth = 1200, quality = 0.8) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = (e) => {
                const img = new Image();

                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Redimensionner si nécessaire
                    if (width > maxWidth) {
                        height = (height * maxWidth) / width;
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Convertir en blob
                    canvas.toBlob(
                        (blob) => {
                            if (blob) {
                                const compressedFile = new File([blob], file.name, {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                resolve(compressedFile);
                            } else {
                                reject(new Error('Compression failed'));
                            }
                        },
                        'image/jpeg',
                        quality
                    );
                };

                img.onerror = reject;
                img.src = e.target.result;
            };

            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    // Générer un placeholder blur (LQIP - Low Quality Image Placeholder)
    generatePlaceholder(img, callback) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = 40;
        canvas.height = 40;

        const tempImg = new Image();
        tempImg.crossOrigin = 'Anonymous';
        tempImg.onload = () => {
            ctx.drawImage(tempImg, 0, 0, 40, 40);
            const placeholder = canvas.toDataURL('image/jpeg', 0.1);
            callback(placeholder);
        };
        tempImg.src = img.src;
    }
}

// Initialiser au chargement
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => new ImageOptimizer());
} else {
    new ImageOptimizer();
}

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ImageOptimizer;
}
