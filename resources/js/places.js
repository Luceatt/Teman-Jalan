/**
 * Places Management JavaScript
 * Handles all frontend interactions for the places management system
 */

class PlacesManager {
    constructor() {
        this.initializeEventListeners();
        this.setupAjax();
    }

    initializeEventListeners() {
        // Search functionality
        this.setupSearch();

        // Form enhancements
        this.setupForms();

        // Geolocation features
        this.setupGeolocation();

        // Image preview
        this.setupImagePreview();
    }

    setupAjax() {
        // Set up default AJAX headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    setupSearch() {
        const searchInput = $('#search');
        const categoryFilter = $('#category');
        let searchTimeout;

        // Real-time search
        searchInput.on('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    this.performSearch(query, categoryFilter.val());
                } else if (query.length === 0) {
                    this.loadPlaces();
                }
            }, 300);
        });

        // Category filter
        categoryFilter.on('change', () => {
            const query = searchInput.val().trim();
            if (query.length >= 2) {
                this.performSearch(query, categoryFilter.val());
            } else {
                this.loadPlaces(categoryFilter.val());
            }
        });
    }

    performSearch(query, categoryId = '') {
        const params = new URLSearchParams({
            query: query,
            category_id: categoryId
        });

        $.get(`{{ route('places.search') }}?${params}`)
            .done((data) => {
                this.updatePlacesDisplay(data.places);
                this.showSearchResults(data.places.length);
            })
            .fail((error) => {
                console.error('Search error:', error);
                showNotification('Search failed. Please try again.', 'error');
            });
    }

    loadPlaces(categoryId = '') {
        let url = window.routes ? window.routes.placesIndex : '/places';
        if (categoryId) {
            url += `?category_id=${categoryId}`;
        }

        $.get(url)
            .done((data) => {
                // Update the places container
                $('#places-container').html($(data).find('#places-container').html());
                this.hideSearchResults();
            })
            .fail((error) => {
                console.error('Load error:', error);
                showNotification('Failed to load places.', 'error');
            });
    }

    updatePlacesDisplay(places) {
        const container = $('#places-container');
        // This would need more sophisticated handling in a real application
        // For now, we'll just update the count
        $('.place-card').hide();
        // Show only matching places (simplified)
    }

    showSearchResults(count) {
        $('#search-results').removeClass('hidden');
        $('#results-count').text(count);
    }

    hideSearchResults() {
        $('#search-results').addClass('hidden');
    }

    setupForms() {
        // Form validation
        $('form[id$="-place-form"]').on('submit', (e) => {
            if (!this.validateForm(e.target)) {
                e.preventDefault();
                return false;
            }

            this.showLoadingState(e.target);
        });

        // Auto-resize textareas
        $('textarea').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required.');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Validate coordinates if provided
        const latitude = form.querySelector('#latitude');
        const longitude = form.querySelector('#longitude');

        if (latitude && longitude && (latitude.value || longitude.value)) {
            if (!this.isValidCoordinate(latitude.value) || !this.isValidCoordinate(longitude.value)) {
                this.showFieldError(latitude, 'Please enter valid coordinates.');
                this.showFieldError(longitude, 'Please enter valid coordinates.');
                isValid = false;
            }
        }

        return isValid;
    }

    isValidCoordinate(coord) {
        const num = parseFloat(coord);
        return !isNaN(num) && num >= -180 && num <= 180;
    }

    showFieldError(field, message) {
        const errorElement = $(field).siblings('.error-message');
        if (errorElement.length) {
            errorElement.text(message);
        } else {
            $(field).after(`<p class="mt-1 text-sm text-red-600 error-message">${message}</p>`);
        }
        field.classList.add('border-red-500');
    }

    clearFieldError(field) {
        $(field).siblings('.error-message').remove();
        field.classList.remove('border-red-500');
    }

    showLoadingState(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        // Reset after timeout (in case of network issues)
        setTimeout(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }, 10000);
    }

    setupGeolocation() {
        $('button[id*="location"]').on('click', (e) => {
            if (!navigator.geolocation) {
                showNotification('Geolocation is not supported by this browser.', 'error');
                return;
            }

            e.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Getting Location...';
            e.target.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.updateCoordinates(position.coords.latitude, position.coords.longitude);
                    e.target.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i>Get Current Location';
                    e.target.disabled = false;
                    showNotification('Location acquired successfully!', 'success');
                },
                (error) => {
                    this.handleGeolocationError(error);
                    e.target.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i>Get Current Location';
                    e.target.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        });
    }

    updateCoordinates(latitude, longitude) {
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
    }

    handleGeolocationError(error) {
        const messages = {
            1: 'Location access denied by user.',
            2: 'Location information unavailable.',
            3: 'Location request timed out.'
        };

        showNotification(messages[error.code] || 'An unknown error occurred.', 'error');
    }

    setupImagePreview() {
        $('input[type="file"][name="image"]').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('Image size must be less than 2MB.', 'error');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showNotification('Please select a valid image file.', 'error');
                    e.target.value = '';
                    return;
                }

                // Show preview for new uploads
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You could show a preview here
                    showNotification('Image selected successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Public API methods
    refreshPlaces() {
        this.loadPlaces();
    }

    filterByCategory(categoryId) {
        $('#category').val(categoryId).trigger('change');
    }
}

// Initialize when DOM is ready
$(document).ready(() => {
    window.placesManager = new PlacesManager();
});

// Export for use in other scripts
window.PlacesManager = PlacesManager;