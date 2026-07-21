/**
 * DzieRes Restaurant - Main JavaScript
 * Version 1.0.0
 */

// ============================================
// DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initNavbar();
    initSearchOverlay();
    initThemeToggle();
    initBackToTop();
    initSmoothScroll();
    initCounterAnimation();
    initAddToCart();
    initFavoriteToggle();
    initQuantityInputs();
    initStarsInput();
    initTableCalendar();
    initGlobalSearch();
    
    // Auto-hide toasts
    setTimeout(function() {
        document.querySelectorAll('.toast.show').forEach(function(toast) {
            var bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        });
    }, 5000);
});

// ============================================
// NAVBAR
// ============================================
function initNavbar() {
    var navbar = document.getElementById('mainNavbar');
    if (!navbar) return;
    
    // Add shadow on scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    
    // Auto-close mobile menu on click
    document.querySelectorAll('.navbar-nav .nav-link').forEach(function(link) {
        link.addEventListener('click', function() {
            var collapse = document.getElementById('navbarNav');
            if (collapse && collapse.classList.contains('show')) {
                var bsCollapse = new bootstrap.Collapse(collapse);
                bsCollapse.hide();
            }
        });
    });
}

// ============================================
// SEARCH OVERLAY
// ============================================
function initSearchOverlay() {
    var toggle = document.getElementById('searchToggle');
    var overlay = document.getElementById('searchOverlay');
    var close = document.getElementById('searchClose');
    var input = document.getElementById('globalSearch');
    
    if (!toggle || !overlay) return;
    
    toggle.addEventListener('click', function() {
        overlay.classList.add('active');
        if (input) {
            setTimeout(function() { input.focus(); }, 300);
        }
    });
    
    if (close) {
        close.addEventListener('click', function() {
            overlay.classList.remove('active');
        });
    }
    
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            overlay.classList.remove('active');
        }
    });
}

// ============================================
// THEME TOGGLE
// ============================================
function initThemeToggle() {
    var toggle = document.getElementById('themeToggle');
    if (!toggle) return;
    
    var html = document.documentElement;
    var icon = toggle.querySelector('i');
    
    // Load saved theme
    var savedTheme = localStorage.getItem('dzieres_theme');
    if (savedTheme) {
        html.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(icon, savedTheme);
    }
    
    toggle.addEventListener('click', function() {
        var current = html.getAttribute('data-bs-theme');
        var newTheme = current === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('dzieres_theme', newTheme);
        updateThemeIcon(icon, newTheme);
        
        // Animate
        toggle.classList.add('rotating');
        setTimeout(function() {
            toggle.classList.remove('rotating');
        }, 500);
    });
}

function updateThemeIcon(icon, theme) {
    if (!icon) return;
    if (theme === 'dark') {
        icon.className = 'fas fa-sun';
    } else {
        icon.className = 'fas fa-moon';
    }
}

// ============================================
// BACK TO TOP
// ============================================
function initBackToTop() {
    var btn = document.getElementById('backToTop');
    if (!btn) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            btn.classList.add('show');
        } else {
            btn.classList.remove('show');
        }
    });
    
    btn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ============================================
// SMOOTH SCROLL
// ============================================
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// ============================================
// COUNTER ANIMATION
// ============================================
function initCounterAnimation() {
    var counters = document.querySelectorAll('.stat-number');
    if (!counters.length) return;
    
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var counter = entry.target;
                var target = parseInt(counter.getAttribute('data-count') || counter.textContent.replace(/[^0-9]/g, ''));
                animateCounter(counter, target);
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(function(counter) {
        observer.observe(counter);
    });
}

function animateCounter(element, target) {
    var current = 0;
    var increment = Math.ceil(target / 60);
    var duration = 2000;
    var step = Math.ceil(duration / 60);
    
    var timer = setInterval(function() {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = current.toLocaleString();
    }, step);
}

// ============================================
// ADD TO CART
// ============================================
function initAddToCart() {
    document.querySelectorAll('.add-to-cart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var foodId = this.getAttribute('data-food-id');
            var quantity = 1;
            
            var qtyInput = this.closest('.food-card')?.querySelector('.qty-input');
            if (qtyInput) {
                quantity = parseInt(qtyInput.value) || 1;
            }
            
            addToCart(foodId, quantity);
        });
    });
}

function addToCart(foodId, quantity) {
    var formData = new FormData();
    formData.append('food_id', foodId);
    formData.append('quantity', quantity || 1);
    
    fetch(BASE_URL + 'cart/add', {
        method: 'POST',
        body: formData
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.success) {
            updateCartCount(data.data.count);
            showToast('success', 'Item added to cart!');
        } else {
            showToast('error', data.message || 'Failed to add item');
        }
    })
    .catch(function(error) {
        console.error('Cart error:', error);
        showToast('error', 'An error occurred');
    });
}

function updateCartCount(count) {
    var badge = document.getElementById('cartCount');
    if (badge) {
        badge.textContent = count;
        badge.classList.add('bounce');
        setTimeout(function() {
            badge.classList.remove('bounce');
        }, 300);
    }
}

// ============================================
// FAVORITE TOGGLE
// ============================================
function initFavoriteToggle() {
    document.querySelectorAll('.favorite-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var foodId = this.getAttribute('data-food-id');
            var icon = this.querySelector('i');
            
            fetch(BASE_URL + 'account/favorites/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'food_id=' + foodId
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    icon.classList.toggle('fas');
                    icon.classList.toggle('far');
                    showToast('success', data.message);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(function(error) {
                console.error('Favorite error:', error);
            });
        });
    });
}

// ============================================
// QUANTITY INPUTS
// ============================================
function initQuantityInputs() {
    document.querySelectorAll('.qty-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var input = this.closest('.qty-group').querySelector('.qty-input');
            if (!input) return;
            
            var current = parseInt(input.value) || 1;
            var min = parseInt(input.getAttribute('min')) || 1;
            var max = parseInt(input.getAttribute('max')) || 99;
            
            if (this.classList.contains('qty-minus')) {
                if (current > min) {
                    input.value = current - 1;
                }
            } else {
                if (current < max) {
                    input.value = current + 1;
                }
            }
            
            // Trigger update
            var event = new Event('change', { bubbles: true });
            input.dispatchEvent(event);
        });
    });
}

// ============================================
// STARS INPUT
// ============================================
function initStarsInput() {
    document.querySelectorAll('.stars-input label').forEach(function(label) {
        label.addEventListener('click', function() {
            var input = this.closest('.stars-input').querySelector('#' + this.getAttribute('for'));
            if (input) {
                input.checked = true;
            }
        });
    });
}

// ============================================
// TABLE CALENDAR (Reservation)
// ============================================
function initTableCalendar() {
    var dateInput = document.getElementById('reservationDate');
    var timeSelect = document.getElementById('reservationTime');
    var guestsSelect = document.getElementById('numberOfGuests');
    var tableContainer = document.getElementById('availableTables');
    
    if (!dateInput || !tableContainer) return;
    
    // Set min date to today
    var today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
    
    function loadAvailableTables() {
        var date = dateInput.value;
        var time = timeSelect ? timeSelect.value : '';
        var guests = guestsSelect ? guestsSelect.value : 2;
        
        if (!date) return;
        
        fetch(BASE_URL + 'api/tables/available?date=' + date + '&time=' + time + '&guests=' + guests)
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success && data.data) {
                renderTableLayout(data.data);
            }
        })
        .catch(function(error) {
            console.error('Table load error:', error);
        });
    }
    
    if (dateInput) dateInput.addEventListener('change', loadAvailableTables);
    if (timeSelect) timeSelect.addEventListener('change', loadAvailableTables);
    if (guestsSelect) guestsSelect.addEventListener('change', loadAvailableTables);
}

function renderTableLayout(tables) {
    var container = document.getElementById('availableTables');
    if (!container) return;
    
    container.innerHTML = '<div class="row g-2">';
    
    tables.forEach(function(table) {
        var statusClass = table.status === 'available' ? 'table-available' : 'table-booked';
        var disabled = table.status !== 'available' ? 'disabled' : '';
        var capacity = table.capacity || 2;
        
        container.innerHTML += '<div class="col-4 col-md-3">' +
            '<div class="table-item ' + statusClass + '" data-table-id="' + table.id + '" ' + disabled + '>' +
                '<i class="fas fa-chair"></i>' +
                '<span class="table-number">' + table.table_number + '</span>' +
                '<small>' + capacity + ' pax</small>' +
            '</div>' +
        '</div>';
    });
    
    container.innerHTML += '</div>';
    
    // Click handler
    container.querySelectorAll('.table-item:not(.disabled)').forEach(function(item) {
        item.addEventListener('click', function() {
            container.querySelectorAll('.table-item').forEach(function(t) {
                t.classList.remove('selected');
            });
            this.classList.add('selected');
            
            var tableInput = document.getElementById('selectedTable');
            if (tableInput) {
                tableInput.value = this.getAttribute('data-table-id');
            }
        });
    });
}

// ============================================
// GLOBAL SEARCH (AJAX)
// ============================================
function initGlobalSearch() {
    var input = document.getElementById('globalSearch');
    var results = document.getElementById('searchResults');
    if (!input || !results) return;
    
    var debounceTimer;
    
    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        var query = this.value.trim();
        
        if (query.length < 2) {
            results.innerHTML = '';
            results.classList.remove('show');
            return;
        }
        
        debounceTimer = setTimeout(function() {
            fetch(BASE_URL + 'api/menu/search?q=' + encodeURIComponent(query))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success && data.data && data.data.length) {
                    var html = '<div class="list-group">';
                    data.data.forEach(function(item) {
                        html += '<a href="' + BASE_URL + 'menu/' + item.slug + '" class="list-group-item list-group-item-action">' +
                            '<div class="d-flex align-items-center">' +
                                '<img src="' + (item.image ? BASE_URL + item.image : BASE_URL + 'assets/images/food-placeholder.svg') + '" alt="' + item.name + '" class="rounded me-3" width="50" height="50" style="object-fit: cover;">' +
                                '<div>' +
                                    '<h6 class="mb-0">' + item.name + '</h6>' +
                                    '<small class="text-muted">' + item.category_name + ' - ' + item.final_price + '</small>' +
                                '</div>' +
                            '</div>' +
                        '</a>';
                    });
                    html += '</div>';
                    results.innerHTML = html;
                    results.classList.add('show');
                } else {
                    results.innerHTML = '<div class="text-center text-muted py-3">No items found</div>';
                    results.classList.add('show');
                }
            })
            .catch(function(error) {
                console.error('Search error:', error);
            });
        }, 300);
    });
}

// ============================================
// TOAST NOTIFICATION
// ============================================
function showToast(type, message) {
    var bgClass = type === 'success' ? 'text-bg-success' : type === 'error' ? 'text-bg-danger' : 'text-bg-info';
    var icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
    
    var toastHtml = '<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">' +
        '<div class="toast show align-items-center ' + bgClass + ' border-0" role="alert">' +
            '<div class="d-flex">' +
                '<div class="toast-body"><i class="fas ' + icon + ' me-2"></i>' + message + '</div>' +
                '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>' +
            '</div>' +
        '</div>' +
    '</div>';
    
    var container = document.createElement('div');
    container.innerHTML = toastHtml;
    document.body.appendChild(container.firstElementChild);
    
    setTimeout(function() {
        var toast = document.querySelector('.toast-container:last-child .toast');
        if (toast) {
            var bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
            setTimeout(function() {
                var parent = toast.closest('.toast-container');
                if (parent) parent.remove();
            }, 500);
        }
    }, 4000);
}

// ============================================
// CONFIRM DIALOG
// ============================================
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// ============================================
// FORMAT CURRENCY
// ============================================
function formatPrice(amount) {
    return '₵' + parseFloat(amount).toFixed(2);
}

// ============================================
// LOADING STATE
// ============================================
function showLoading(button) {
    if (!button) return;
    var originalText = button.innerHTML;
    button.setAttribute('data-original-text', originalText);
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    button.disabled = true;
}

function hideLoading(button) {
    if (!button) return;
    var originalText = button.getAttribute('data-original-text');
    if (originalText) {
        button.innerHTML = originalText;
    }
    button.disabled = false;
}

// ============================================
// IMAGE GALLERY LIGHTBOX
// ============================================
function openLightbox(imageSrc, title) {
    var modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = '<div class="modal-dialog modal-xl modal-dialog-centered">' +
        '<div class="modal-content bg-transparent border-0">' +
            '<div class="modal-body p-0 text-center">' +
                '<button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>' +
                '<img src="' + imageSrc + '" alt="' + (title || 'Gallery Image') + '" class="img-fluid rounded">' +
                (title ? '<p class="text-white mt-2 mb-0">' + title + '</p>' : '') +
            '</div>' +
        '</div>' +
    '</div>';
    
    document.body.appendChild(modal);
    var bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

// ============================================
// CHART.JS HELPERS (for Admin)
// ============================================
function createChart(canvasId, type, data, options) {
    var canvas = document.getElementById(canvasId);
    if (!canvas) return null;
    
    var ctx = canvas.getContext('2d');
    return new Chart(ctx, {
        type: type,
        data: data,
        options: options || {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20, usePointStyle: true }
                }
            }
        }
    });
}

// Expose globally
window.addToCart = addToCart;
window.showToast = showToast;
window.confirmAction = confirmAction;
window.formatPrice = formatPrice;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.openLightbox = openLightbox;
window.createChart = createChart;