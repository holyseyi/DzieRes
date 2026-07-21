/**
 * DzieRes Admin Panel - JavaScript
 * Version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    initNotifications();
    initDataTables();
    initImagePreview();
    initConfirmActions();
    initStatusUpdates();
    initTables();
});

// ============================================
// NOTIFICATIONS
// ============================================
function initNotifications() {
    var notifBtn = document.getElementById('notificationBtn');
    if (!notifBtn) return;

    var list = document.getElementById('notificationList');
    var badge = document.getElementById('notifCount');
    var unread = 0;

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function loadNotifications() {
        fetch(BASE_URL + 'admin/api/notifications')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success || !data.data) return;
                var items = data.data;
                unread = 0;
                items.forEach(function(n) { if (!n.is_read) unread++; });

                if (badge) {
                    badge.textContent = unread;
                    badge.style.display = unread > 0 ? 'block' : 'none';
                }

                if (!list) return;
                if (items.length === 0) {
                    list.innerHTML = '<div class="text-center text-muted py-4">No notifications</div>';
                    return;
                }

                var html = '';
                items.forEach(function(n) {
                    var icon = n.type === 'order' ? 'fa-receipt'
                        : n.type === 'reservation' ? 'fa-calendar-check'
                        : n.type === 'review' ? 'fa-star'
                        : 'fa-bell';
                    var time = n.created_at ? n.created_at : '';
                    var readClass = n.is_read ? '' : ' fw-semibold bg-light';

                    // Link to the relevant admin section for this notification
                    var href = '#';
                    if (n.link) {
                        href = /^https?:\/\//.test(n.link) ? n.link : BASE_URL + n.link.replace(/^\//, '');
                    } else if (n.type === 'order') {
                        href = BASE_URL + 'admin/orders';
                    } else if (n.type === 'reservation') {
                        href = BASE_URL + 'admin/reservations';
                    } else if (n.type === 'review') {
                        href = BASE_URL + 'admin/reviews';
                    } else {
                        href = BASE_URL + 'admin';
                    }

                    html += '<a href="' + href + '" class="dropdown-item notification-item' + readClass + '" data-id="' + n.id + '">' +
                        '<div class="d-flex gap-2">' +
                        '<i class="fas ' + icon + ' text-gold mt-1"></i>' +
                        '<div class="flex-grow-1">' +
                        '<div class="small">' + escapeHtml(n.message) + '</div>' +
                        '<div class="text-muted" style="font-size:0.75rem;">' + escapeHtml(time) + '</div>' +
                        '</div></div></a>';
                });
                list.innerHTML = html;
            })
            .catch(function() {});
    }

    // Mark individual notification as read when clicked (then navigate to its link)
    if (list) {
        list.addEventListener('click', function(e) {
            var item = e.target.closest('.notification-item');
            if (!item) return;
            var id = item.getAttribute('data-id');
            var fd = new FormData();
            fd.append('_csrf_token', getCsrfToken());
            fd.append('id', id);
            fetch(BASE_URL + 'admin/api/notifications/read', { method: 'POST', body: fd })
                .then(function() { loadNotifications(); })
                .catch(function() {});
            // Do NOT preventDefault -> the anchor navigates to the linked section
        });
    }

    // Mark all as read
    var markAll = document.getElementById('markAllRead');
    if (markAll) {
        markAll.addEventListener('click', function(e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append('_csrf_token', getCsrfToken());
            fetch(BASE_URL + 'admin/api/notifications/read', { method: 'POST', body: fd })
                .then(function() { loadNotifications(); })
                .catch(function() {});
        });
    }

    // Load on open + every 30s
    notifBtn.addEventListener('shown.bs.dropdown', loadNotifications);
    loadNotifications();
    setInterval(loadNotifications, 30000);
}

// ============================================
// DATA TABLES
// ============================================
function initDataTables() {
    document.querySelectorAll('.data-table').forEach(function(table) {
        var searchInput = table.closest('.card')?.querySelector('.table-search');
        if (!searchInput) return;
        
        searchInput.addEventListener('keyup', function() {
            var search = this.value.toLowerCase();
            var rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });
    });
}

// ============================================
// IMAGE PREVIEW
// ============================================
function initImagePreview() {
    document.querySelectorAll('.image-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var preview = this.closest('.image-upload').querySelector('.image-preview');
            if (!preview || !this.files || !this.files[0]) return;
            
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        });
    });
}

// ============================================
// CONFIRM ACTIONS
// ============================================
function initConfirmActions() {
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            var message = this.getAttribute('data-confirm') || 'Are you sure?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

// ============================================
// STATUS UPDATES (AJAX)
// ============================================
function initStatusUpdates() {
    document.querySelectorAll('.status-update').forEach(function(select) {
        select.addEventListener('change', function() {
            var url = this.getAttribute('data-url');
            var status = this.value;
            var id = this.getAttribute('data-id');
            
            if (!url || !id) return;
            
            var formData = new FormData();
            formData.append('status', status);
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast('success', 'Status updated successfully');
                } else {
                    showToast('error', data.message || 'Failed to update status');
                }
            })
            .catch(function() {
                showToast('error', 'An error occurred');
            });
        });
    });
}

// ============================================
// BULK SELECT
// ============================================
function toggleBulkSelect(checkbox) {
    var checkboxes = document.querySelectorAll('.bulk-item');
    checkboxes.forEach(function(cb) {
        cb.checked = checkbox.checked;
    });
}

function getSelectedIds() {
    var ids = [];
    document.querySelectorAll('.bulk-item:checked').forEach(function(cb) {
        ids.push(cb.value);
    });
    return ids;
}

// ============================================
// TABLES (Floor management: add / status / delete)
// ============================================
function initTables() {
    // Add table
    var tableForm = document.getElementById('tableForm');
    if (tableForm) {
        tableForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(tableForm);
            fetch(BASE_URL + 'admin/tables/store', {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast('success', data.message || 'Table added');
                    var modal = bootstrap.Modal.getInstance(document.getElementById('tableModal'));
                    if (modal) modal.hide();
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to add table');
                }
            })
            .catch(function() {
                showToast('error', 'An error occurred');
            });
        });
    }

    // Set table status
    document.querySelectorAll('.table-status').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            var status = this.getAttribute('data-status');
            var formData = new FormData();
            formData.append('_csrf_token', getCsrfToken());
            formData.append('status', status);
            fetch(BASE_URL + 'admin/tables/' + id + '/update', {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast('success', data.message || 'Status updated');
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to update');
                }
            })
            .catch(function() {
                showToast('error', 'An error occurred');
            });
        });
    });

    // Delete table
    document.querySelectorAll('.table-del').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            if (!confirm('Delete this table?')) return;
            var id = this.getAttribute('data-id');
            var formData = new FormData();
            formData.append('_csrf_token', getCsrfToken());
            fetch(BASE_URL + 'admin/tables/' + id + '/delete', {
                method: 'POST',
                body: formData
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    showToast('success', data.message || 'Table deleted');
                    location.reload();
                } else {
                    showToast('error', data.message || 'Failed to delete');
                }
            })
            .catch(function() {
                showToast('error', 'An error occurred');
            });
        });
    });
}

// Small helper to grab the current CSRF token from a hidden field
function getCsrfToken() {
    var field = document.querySelector('input[name="_csrf_token"]');
    return field ? field.value : '';
}

// ============================================
// EXPORT TABLE
// ============================================
function exportTable(tableId, filename) {
    var table = document.getElementById(tableId);
    if (!table) return;
    
    var csv = [];
    var rows = table.querySelectorAll('tr');
    
    rows.forEach(function(row) {
        var cols = row.querySelectorAll('td, th');
        var rowData = [];
        cols.forEach(function(col) {
            rowData.push('"' + col.textContent.trim() + '"');
        });
        csv.push(rowData.join(','));
    });
    
    var csvContent = csv.join('\n');
    var blob = new Blob([csvContent], { type: 'text/csv' });
    var url = window.URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = (filename || 'export') + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Expose functions globally
window.toggleBulkSelect = toggleBulkSelect;
window.getSelectedIds = getSelectedIds;
window.exportTable = exportTable;