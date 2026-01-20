// Pengiriman Barang - Custom JavaScript Functions
class ProductRepeaterManager {
    constructor() {
        this.addedProducts = new Map(); // Track products added in this session (for merging)
        this.initializeRepeater();
    }

    initializeRepeater() {
        this.setupAddProductForm();
        this.setupDynamicEventHandlers();
    }

    setupAddProductForm() {
        const addForm = document.getElementById('add-product-form');
        if (addForm) {
            addForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddProduct();
            });
        }
    }

    setupDynamicEventHandlers() {
        // Dynamic event delegation for edit/delete buttons on new rows
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('edit-qty')) {
                e.preventDefault();
                this.handleEditQty(e.target);
            } else if (e.target.classList.contains('delete-detail')) {
                e.preventDefault();
                this.handleDeleteDetail(e.target);
            }
        });

        // Dynamic event for save quantity
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-success') && e.target.textContent.includes('Save')) {
                e.preventDefault();
                this.handleSaveQty(e.target);
            }
        });
    }

    handleAddProduct() {
        const form = document.getElementById('add-product-form');
        if (!form) return;

        const productSelect = form.querySelector('[name="kodeproduk"]');
        const qtyInput = form.querySelector('[name="qty"]');
        const kodeproduk = productSelect.value;
        const qty = parseInt(qtyInput.value);

        // Validate
        if (!kodeproduk) {
            this.showError('Silakan pilih produk.');
            return;
        }
        if (qty < 1) {
            this.showError('Kuantitas minimal 1.');
            return;
        }

        // Check if product already exists in table
        const existingRow = document.querySelector(`.product-row[data-kodeproduk="${kodeproduk}"]`);
        if (existingRow) {
            // Merge quantities
            const existingQtyInput = existingRow.querySelector('.qty-input');
            const currentQty = parseInt(existingQtyInput.value) || 0;
            const newQty = currentQty + qty;
            existingQtyInput.value = newQty;
            existingQtyInput.setAttribute('data-original', newQty);

            // Highlight the merged row
            existingRow.classList.add('highlight-merge');
            setTimeout(() => existingRow.classList.remove('highlight-merge'), 1500);

            // Update total
            this.updateTotalQuantity();

            // Reset form
            this.resetAddForm();

            // Show success message
            const productName = productSelect.options[productSelect.selectedIndex].text.split(' (')[0];
            this.showSuccess(`Kuantitas produk ${productName} berhasil ditambahkan.`);

            return;
        }

        // Get product details for new row
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productName = selectedOption.text.split(' (')[0];
        const satuan = selectedOption.text.match(/\(([^)]+)\)/)?.[1] || '';

        // Add new row
        this.addProductRow({
            kodeproduk: kodeproduk,
            nama: productName,
            satuan: satuan,
            qty: qty
        });

        // Reset form
        this.resetAddForm();

        // Show success message
        this.showSuccess(`Produk ${productName} berhasil ditambahkan.`);
    }

    addProductRow(productData) {
        const tbody = document.getElementById('detail-tbody');
        if (!tbody) return;

        // Hide empty row
        const emptyRow = document.getElementById('empty-row');
        if (emptyRow) {
            emptyRow.style.display = 'none';
        }

        // Create new row HTML
        const rowHtml = `
            <tr class="product-row new-product-row" data-kodeproduk="${productData.kodeproduk}">
                <td>${productData.kodeproduk}</td>
                <td>${productData.nama}</td>
                <td>${productData.satuan}</td>
                <td>
                    <input type="number" class="form-control qty-input" value="${productData.qty}"
                           data-original="${productData.qty}" min="1">
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-warning edit-qty">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-detail">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;

        // Insert at top of tbody
        tbody.insertAdjacentHTML('afterbegin', rowHtml);

        // Update total
        this.updateTotalQuantity();

        // Track in addedProducts map
        this.addedProducts.set(productData.kodeproduk, productData.qty);
    }

    resetAddForm() {
        const form = document.getElementById('add-product-form');
        if (form) {
            form.reset();
            // Reset select2 if used
            const select = form.querySelector('select');
            if (select && select.select2) {
                select.select2('val', '');
            }
        }
    }

    handleEditQty(button) {
        const row = button.closest('.product-row');
        const input = row.querySelector('.qty-input');
        const originalQty = input.getAttribute('data-original');

        input.readOnly = false;
        input.style.backgroundColor = '#fff';
        input.focus();

        button.innerHTML = '<i class="fa fa-save"></i>';
        button.classList.remove('btn-warning');
        button.classList.add('btn-success');
    }

    handleSaveQty(button) {
        const row = button.closest('.product-row');
        const input = row.querySelector('.qty-input');
        const newQty = parseInt(input.value);

        if (newQty < 1) {
            this.showError('Kuantitas minimal 1.');
            return;
        }

        const originalQty = parseInt(input.getAttribute('data-original'));

        if (newQty === originalQty) {
            // No change, just reset UI
            input.readOnly = true;
            input.style.backgroundColor = '#f8f9fa';
            button.innerHTML = '<i class="fa fa-edit"></i>';
            button.classList.remove('btn-success');
            button.classList.add('btn-warning');
            return;
        }

        // Update data-original
        input.setAttribute('data-original', newQty);

        // Reset UI
        input.readOnly = true;
        input.style.backgroundColor = '#f8f9fa';
        button.innerHTML = '<i class="fa fa-edit"></i>';
        button.classList.remove('btn-success');
        button.classList.add('btn-warning');

        // Update total
        this.updateTotalQuantity();

        // Update tracking if this was an added product
        const kodeproduk = row.getAttribute('data-kodeproduk');
        if (this.addedProducts.has(kodeproduk)) {
            this.addedProducts.set(kodeproduk, newQty);
        }

        this.showSuccess('Kuantitas berhasil diupdate.');
    }

    handleDeleteDetail(button) {
        const row = button.closest('.product-row');
        const kodeproduk = row.getAttribute('data-kodeproduk');
        const productName = row.querySelector('td:nth-child(2)').textContent;

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus produk ${productName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove from tracking if it was added in this session
                this.addedProducts.delete(kodeproduk);

                // Remove row
                row.remove();

                // Update total
                this.updateTotalQuantity();

                // Show empty row if no products left
                const tbody = document.getElementById('detail-tbody');
                const remainingRows = tbody.querySelectorAll('.product-row');
                if (remainingRows.length === 0) {
                    const emptyRow = document.getElementById('empty-row');
                    if (emptyRow) {
                        emptyRow.style.display = '';
                    }
                }

                this.showSuccess(`Produk ${productName} berhasil dihapus.`);
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

        // Update jenis count
        const jenisElement = document.querySelector('#jenis-count');
        if (jenisElement) {
            const productRows = document.querySelectorAll('.product-row');
            jenisElement.textContent = productRows.length;
        }
    }

    collectAllProducts() {
        const products = [];
        const productRows = document.querySelectorAll('.product-row');

        productRows.forEach(row => {
            const kodeproduk = row.getAttribute('data-kodeproduk');
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseInt(qtyInput.value) || 0;

            products.push({
                kodeproduk: kodeproduk,
                qty: qty
            });
        });

        return products;
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
}

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
    window.productRepeaterManager = new ProductRepeaterManager();

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
