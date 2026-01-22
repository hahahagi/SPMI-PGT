</div>
</main>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.table-datatable').DataTable({
            "ordering": false
        });

        // --- SWEETALERT HANDLING ---
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const msg = urlParams.get('msg');

        if (status) {
            let swalOptions = {};

            if (status === 'login_sukses') {
                swalOptions = {
                    icon: 'success',
                    title: 'Login Berhasil',
                    text: 'Selamat datang kembali!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                };
            } else if (status === 'sukses') {
                swalOptions = {
                    icon: 'success',
                    title: 'Berhasil!',
                    text: msg || 'Data berhasil disimpan.',
                    timer: 2000,
                    showConfirmButton: false
                };
            } else if (status === 'upload_sukses') {
                swalOptions = {
                    icon: 'success',
                    title: 'Upload Berhasil!',
                    text: 'File dokumen telah dikirim.',
                    timer: 2000,
                    showConfirmButton: false
                };
            } else if (status === 'hapus_sukses') {
                swalOptions = {
                    icon: 'success',
                    title: 'Terhapus!',
                    text: 'Data telah berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false
                };
            } else if (status === 'gagal') {
                swalOptions = {
                    icon: 'error',
                    title: 'Gagal!',
                    text: msg || 'Terjadi kesalahan saat memproses data.'
                };
            } else if (status === 'gagal_username') {
                swalOptions = {
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Username sudah digunakan, silakan pilih yang lain.'
                };
            }

            if (swalOptions.icon) {
                Swal.fire(swalOptions).then(() => {
                    // Bersihkan parameter status dari URL
                    const url = new URL(window.location.href);
                    url.searchParams.delete('status');
                    url.searchParams.delete('msg');

                    window.history.replaceState({}, '', url.toString());
                });
            }
        }
    });

    // --- CONFIRMATION HANDLERS (GLOBAL) ---

    // Fungsi Konfirmasi Hapus
    window.confirmDelete = function(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Fungsi Konfirmasi Logout
    window.confirmLogout = function(e, url) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Anda akan keluar dari sesi aplikasi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

<!-- GLOBAL PREVIEW MODAL -->
<div class="modal fade" id="modalPreviewFile" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="height: 90vh;">
        <div class="modal-content h-100">
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title fw-bold text-truncate" id="previewTitle" style="max-width: 80%;">File Preview</h6>
                <div class="ms-auto">
                    <a href="#" id="btnDownloadPreview" class="btn btn-sm btn-primary me-2" download><i class="bi bi-download"></i> Download</a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-0 h-100 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" id="previewBody">
                <!-- Content injected by JS -->
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(path, filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const titleEl = document.getElementById('previewTitle');
        const bodyEl = document.getElementById('previewBody');
        const downloadBtn = document.getElementById('btnDownloadPreview');

        titleEl.textContent = filename;

        // Atur link download (hapus mode=inline, ganti jadi mode=download)
        let downloadPath = path.replace('mode=inline', 'mode=download');
        if (!downloadPath.includes('mode=download')) {
            downloadPath += (downloadPath.includes('?') ? '&' : '?') + 'mode=download';
        }
        downloadBtn.setAttribute('href', downloadPath);

        // Reset Content
        bodyEl.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';

        const myModal = new bootstrap.Modal(document.getElementById('modalPreviewFile'));
        myModal.show();

        setTimeout(() => {
            let content = '';
            /* Supported Web Formats */
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'].includes(ext)) {
                content = `<img src="${path}" class="img-fluid" style="max-height: 100%; max-width: 100%; box-shadow: 0 0 10px rgba(0,0,0,0.1);">`;
            } else if (['pdf'].includes(ext)) {
                content = `<iframe src="${path}" style="width:100%; height:100%; border:none;"></iframe>`;
            } else if (['txt', 'html', 'css', 'js', 'json', 'xml'].includes(ext)) {
                content = `<iframe src="${path}" style="width:100%; height:100%; border:none; background:white;"></iframe>`;
            }
            /* Unsupported Formats */
            else {
                content = `
                <div class="text-center p-5 bg-white rounded shadow-sm">
                    <i class="bi bi-file-earmark-break display-1 text-muted mb-3"></i>
                    <h5 class="fw-bold">Preview Tidak Tersedia</h5>
                    <p class="text-muted mb-4">
                        Format file <strong>.${ext.toUpperCase()}</strong> pertinjau tidak tersedia.<br>
                        Silakan download file untuk melihat isinya.
                    </p>
                    <a href="${path}" class="btn btn-primary" download>
                        <i class="bi bi-download me-2"></i> Download File
                    </a>
                </div>
            `;
            }
            bodyEl.innerHTML = content;
        }, 300);
    }
</script>
</body>

</html>