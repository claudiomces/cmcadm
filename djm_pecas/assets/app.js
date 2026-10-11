document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.main-nav');

    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', () => {
            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', String(!isOpen));
            navigation.classList.toggle('is-open', !isOpen);
        });
    }

    const featuredCarousel = document.querySelector('[data-featured-carousel]');
    if (featuredCarousel) {
        const featuredImage = featuredCarousel.querySelector('[data-featured-image]');
        const featuredIndex = featuredCarousel.querySelector('[data-featured-index]');
        const featuredName = featuredCarousel.querySelector('[data-featured-name]');
        const featuredDetails = featuredCarousel.querySelector('[data-featured-details]');
        const modalButton = featuredCarousel.querySelector('[data-featured-modal]');
        const selectors = Array.from(featuredCarousel.querySelectorAll('[data-featured-select]'));
        let activeIndex = 0;
        let rotationTimer;

        const showFeaturedProduct = (index) => {
            const selector = selectors[index];
            if (!selector) return;

            activeIndex = index;
            featuredImage.src = selector.dataset.productImage || '';
            featuredImage.alt = selector.dataset.productName || '';
            featuredIndex.textContent = `DJM / ${String(index + 1).padStart(2, '0')}`;
            featuredName.textContent = selector.dataset.productName || '';
            featuredDetails.textContent = selector.dataset.productDetails || '';
            modalButton.dataset.productName = selector.dataset.productName || '';
            modalButton.dataset.productImage = selector.dataset.productImage || '';
            modalButton.dataset.productDetails = selector.dataset.productDetails || '';

            selectors.forEach((item, itemIndex) => {
                const isActive = itemIndex === index;
                item.classList.toggle('is-active', isActive);
                item.setAttribute('aria-pressed', String(isActive));
            });
        };

        if (selectors.length > 1) {
            const startRotation = () => {
                window.clearInterval(rotationTimer);
                rotationTimer = window.setInterval(() => {
                    showFeaturedProduct((activeIndex + 1) % selectors.length);
                }, 5000);
            };

            selectors.forEach((selector, index) => {
                selector.addEventListener('click', () => {
                    showFeaturedProduct(index);
                    startRotation();
                });
            });
            startRotation();
        }
    }

    const modal = document.querySelector('#product-modal');
    if (modal) {
        const modalTitle = modal.querySelector('#modal-title');
        const modalImage = modal.querySelector('.modal-image');
        const modalDetails = modal.querySelector('.modal-details');
        const closeButton = modal.querySelector('.modal-close');

        document.querySelectorAll('[data-open-details]').forEach((button) => {
            button.addEventListener('click', () => {
                modalTitle.textContent = button.dataset.productName || '';
                modalImage.src = button.dataset.productImage || '';
                modalImage.alt = button.dataset.productName || '';
                modalDetails.textContent = button.dataset.productDetails || '';
                modal.hidden = false;
                closeButton.focus();
            });
        });

        const closeModal = () => {
            modal.hidden = true;
        };

        closeButton.addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) closeModal();
        });
    }

    document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Deseja realmente excluir esta peça?')) event.preventDefault();
        });
    });

    const detailsField = document.querySelector('#details');
    const characterCount = document.querySelector('#character-count');
    if (detailsField && characterCount) {
        const updateCount = () => {
            characterCount.textContent = `${Array.from(detailsField.value).length} / 400 caracteres`;
        };
        detailsField.addEventListener('input', updateCount);
        updateCount();
    }

});
