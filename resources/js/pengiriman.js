// Pengiriman Barang - Custom JavaScript Functions
class PengirimanManager {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Product selection with stock validation
        this.setupProductSelection();

        // Real-time calculations
        this.setupCalculations();

        // Form state management
        this.setupFormState();
    }

    setupProductSelection() {
        const productSelect = document.getElementById('product-select');
        if (productSelect) {
            productSelect.addEventListener('change', (e) => {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const stockInfo = selectedOption ? selectedOption.textContent.match(/Stok: (\d+)/) : null;
                const currentStock = stockInfo ? parseInt(stockInfo[1]) : 0;

                if (currentStock < 10) {
                    this.showWarning('Stok produk tersedia rendah! Stok saat ini: ' + currentStock);
                }
            });
        }
    }

    setupCalculations() {
        // Auto-update total quantity when items change
        this.updateTotalQuantity();

        // Setup quantity change listeners
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('qty-input')) {
                this.updateTotalQuantity();
            }
        });
    }

    updateTotalQuantity() {
        const qtyInputs = document.querySelectorAll('.qty-input');
        let totalQty = 0;

        qtyInputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            totalQty += qty;
        });

        const totalElement = document.getElementById('total-qty');
        if (totalElement) {
            totalElement.textContent = totalQty;
        }
    }

    setupFormState() {
        // Setup form validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return false;
                }

                this.setFormLoading(form, true);
            });
        });
    }

    validateForm(form) {
        // Check if form has required fields
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                this.showFieldError(field, 'Field ini harus diisi.');
            } else {
                this.clearFieldError(field);
            }
        });

        // Special validation for detail items
        if (form.id === 'add-product-form') {
            return this.validateProductForm(form);
        }

        return isValid;
    }

    validateProductForm(form) {
        const qty = parseInt(form.querySelector('[name="qty"]').value) || 0;
        const product = form.querySelector('[name="kodeproduk"]').value;

        if (!product) {
            this.showError('Silakan pilih produk.');
            return false;
        }

        if (qty < 1) {
            this.showError('Kuantitas minimal 1.');
            return false;
        }

        return true;
    }

    setFormLoading(form, isLoading) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            if (isLoading) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || '<i class="fa fa-save"></i> Simpan';
            }
        }
    }

    showFieldError(field, message) {
        field.classList.add('is-invalid');
        field.focus();

        let errorElement = field.parentNode.querySelector('.text-danger');
        if (!errorElement) {
            errorElement = document.createElement('span');
            errorElement.className = 'text-danger';
            errorElement.style.fontSize = '0.875rem';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.text-danger');
        if (errorElement) {
            errorElement.remove();
        }
    }

    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonColor: '#d33'
        });
    }

    showWarning(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: message,
            confirmButtonColor: '#ffc107'
        });
    }

    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Add product with AJAX
    addProduct(kodekirim, kodeproduk, qty) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('kodekirim', kodekirim);
        formData.append('kodeproduk', kodeproduk);
        formData.append('qty', qty);

        fetch('/pengiriman/add-product', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccess(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                this.showError(data.message);
            }
        })
        .catch(error => {
            this.showError('Gagal menambah produk. Silakan coba kembali.');
        });
    }

    // Update detail quantity
    updateDetailQty(detailId, newQty) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('qty', newQty);

        fetch(`/pengiriman/update-detail-qty/${detailId}`, {
            method: 'PUT',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showSuccess(data.message);
                document.getElementById('total-qty').textContent = data.total_qty;
            } else {
                this.showError(data.message);
            }
        })
        .catch(error => {
            this.showError('Gagal update kuantitas.');
        });
    }

    // Remove detail item
    removeDetail(detailId) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus item ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/pengiriman/remove-detail/${detailId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.showSuccess(data.message);
                        const row = document.querySelector(`.product-row[data-id="${detailId}"]`);
                        if (row) {
                            row.remove();
                        }
                        document.getElementById('total-qty').textContent = data.total_qty;

                        // Show empty row if needed
                        const tbody = document.getElementById('detail-tbody');
                        const remainingRows = tbody.querySelectorAll('.product-row');
                        if (remainingRows.length === 0) {
                            document.getElementById('empty-row').style.display = '';
                        }
                    } else {
                        this.showError(data.message);
                    }
                })
                .catch(error => {
                    this.showError('Gagal hapus item.');
                });
            }
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.pengirimanManager = new PengirimanManager();

    // Make functions globally accessible
    window.addPengirimanProduct = (kodekirim, kodeproduk, qty) => {
        window.pengirimanManager.addProduct(kodekirim, kodeproduk, qty);
    };

    window.updatePengirimanDetailQty = (detailId, newQty) => {
        window.pengirimanManager.updateDetailQty(detailId, newQty);
    };

    window.removePengirimanDetail = (detailId) => {
        window.pengirimanManager.removeDetail(detailId);
    };
});
