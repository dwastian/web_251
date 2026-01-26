$(document).ready(function () {
    // Initialize all DataTables
    $(".datatable").DataTable({
        language: {
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya",
            },
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data dalam tabel",
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Semua"],
        ],
    });

    // SweetAlert2 confirmation for delete buttons
    $(".delete-form").on("submit", function (e) {
        e.preventDefault();
        const form = this;
        const itemName = $(form).data("item-name") || "item ini";

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: `Data ${itemName} akan dihapus secara permanen!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Show success messages with SweetAlert2
    if (typeof successMessage !== "undefined" && successMessage) {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: successMessage,
            showConfirmButton: false,
            timer: 1500,
        });
    }

    // Show error messages with SweetAlert2
    if (typeof errorMessage !== "undefined" && errorMessage) {
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: errorMessage,
            confirmButtonColor: "#dc3545",
        });
    }
});

// Helper functions for global use
function showLoading() {
    Swal.fire({
        title: "Memproses...",
        html: "Mohon tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function hideLoading() {
    Swal.close();
}

function showSuccess(message) {
    Swal.fire({
        icon: "success",
        title: "Berhasil!",
        text: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

function showError(message) {
    Swal.fire({
        icon: "error",
        title: "Error!",
        text: message,
        confirmButtonColor: "#dc3545",
    });
}

// Auto-hide alerts
setTimeout(function () {
    $(".alert").fadeOut("slow");
}, 5000);
