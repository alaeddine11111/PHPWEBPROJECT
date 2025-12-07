// Consolidated site JavaScript
(function() {
    'use strict';

    // quick load marker for debugging
    try { console.log('assets/js/script.js loaded'); } catch(e){}

    // Attach a guarded search input listener to any page that has an element with id "searchInput"
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return;

        searchInput.addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    }

    // Animate stats cards when DOM is ready
    function initStatsCards() {
        const statsCards = document.querySelectorAll('.stats-card');
        if (!statsCards.length) return;

        statsCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Run init when DOM is ready. If DOMContentLoaded already fired, run immediately.
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            try { fn(); } catch (e) { console.error(e); }
        }
    }

    ready(function() {
        initSearch();
        initStatsCards();
        initEditModal();
    });

    // Fill edit modal fields from the clicked edit button's data-* attributes
    function initEditModal() {
        var editModal = document.getElementById('editProductModal');
        if (!editModal) return;

        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            if (!button) return;

            var id = button.getAttribute('data-id') || '';
            var name = button.getAttribute('data-name') || '';
            var category = button.getAttribute('data-category') || '';
            var quantity = button.getAttribute('data-quantity') || '';
            var location = button.getAttribute('data-location') || '';
            var image = button.getAttribute('data-image') || '';

            // Populate form fields
            var fldId = document.getElementById('edit_product_id');
            var fldName = document.getElementById('edit_product_name');
            var fldCategory = document.getElementById('edit_category');
            var fldQuantity = document.getElementById('edit_quantity');
            var fldLocation = document.getElementById('edit_location');
            var fldImage = document.getElementById('edit_product_image');

            if (fldId) fldId.value = id;
            if (fldName) fldName.value = name;
            if (fldCategory) {
                try { fldCategory.value = category; } catch(e) {}
            }
            if (fldQuantity) fldQuantity.value = quantity;
            if (fldLocation) {
                try { fldLocation.value = location; } catch(e) {}
            }
            if (fldImage) fldImage.value = image;
        });
    }
})();
