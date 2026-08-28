<!-- Add QR Modal -->
<div class="modal fade" id="addQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qrwallet-modal-content">
            <div class="modal-body p-4 position-relative">
                <button type="button" class="btn-close qrwallet-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h5 class="mb-3" style="color: #000;">Add QR</h5>

                <form action="{{ route('qr-codes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small text-muted">Label</label>
                        <input type="text" name="label" class="form-control" style="border-color: #d1ccc8;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Payment Platform</label>
                        <select name="platform_id" class="form-select" style="border-color: #d1ccc8;" required>
                            <option value="">Select platform</option>
                            @foreach ($platforms as $platform)
                                <option value="{{ $platform->id }}">{{ $platform->platform_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Upload QR Code</label>
                        <input type="file" name="qr_image" accept=".png,.jpg,.jpeg" class="form-control" style="border-color: #d1ccc8;" required>
                        <div class="form-text">Drag & drop or browse file. PNG, JPG up to 5MB</div>
                    </div>

                    <button type="submit" class="btn w-100" style="background-color: #855f4a; color: #fff;">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit QR Modal -->
<div class="modal fade" id="editQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qrwallet-modal-content">
            <div class="modal-body p-4 position-relative">
                <button type="button" class="btn-close qrwallet-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h5 class="mb-3" style="color: #000;">Edit QR</h5>

                <form id="editQrForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small text-muted">Label</label>
                        <input type="text" name="label" id="editQrLabel" class="form-control" style="border-color: #d1ccc8;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Payment Platform</label>
                        <select name="platform_id" id="editQrPlatform" class="form-select" style="border-color: #d1ccc8;" required>
                            <option value="">Select platform</option>
                            @foreach ($platforms as $platform)
                                <option value="{{ $platform->id }}">{{ $platform->platform_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Upload QR Code</label>
                        <input type="file" name="qr_image" accept=".png,.jpg,.jpeg" class="form-control" style="border-color: #d1ccc8;">
                        <div class="form-text">Drag & drop or browse file. PNG, JPG up to 5MB (leave blank to keep current image)</div>
                    </div>

                    <button type="submit" class="btn w-100" style="background-color: #855f4a; color: #fff;">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View QR Modal -->
<div class="modal fade" id="viewQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qrwallet-modal-content">
            <div class="modal-body p-4 position-relative text-center">
                <button type="button" class="btn-close qrwallet-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

                <h6 class="fw-semibold mb-0" id="viewQrLabel" style="color: #000;"></h6>
                <div class="text-muted small mb-3" id="viewQrPlatform"></div>

                <img id="viewQrImage" src="" alt="QR Code" class="img-fluid rounded mb-3" style="max-width: 260px;">

                <div>
                    <a id="viewQrDownload" href="" class="btn btn-link" style="color: #855f4a;">
                        <i class="bi bi-download fs-5"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete QR Confirmation Modal -->
<div class="modal fade" id="deleteQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qrwallet-modal-content">
            <div class="modal-body text-center py-4">
                <p class="mb-1">Are you sure you want to delete?</p>
                <p class="fw-bold mb-3">
                    <span id="deleteQrLabel"></span><br>
                    <small class="text-muted" id="deleteQrPlatform"></small>
                </p>

                <form id="deleteQrForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn text-white" style="background-color: #855f4a;">
                        Delete
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        No
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editQrModal = document.getElementById('editQrModal');

    editQrModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const label = button.getAttribute('data-label');
        const platformId = button.getAttribute('data-platform-id');

        const form = document.getElementById('editQrForm');
        form.action = '/qr-codes/' + id;

        document.getElementById('editQrLabel').value = label;
        document.getElementById('editQrPlatform').value = platformId;
    });

    const viewQrModal = document.getElementById('viewQrModal');

    viewQrModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        document.getElementById('viewQrLabel').textContent = button.getAttribute('data-label');
        document.getElementById('viewQrPlatform').textContent = button.getAttribute('data-platform');
        document.getElementById('viewQrImage').src = button.getAttribute('data-image');
        document.getElementById('viewQrDownload').href = button.getAttribute('data-download');
    });

    const deleteQrModal = document.getElementById('deleteQrModal');

    deleteQrModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        document.getElementById('deleteQrLabel').textContent = button.getAttribute('data-label');
        document.getElementById('deleteQrPlatform').textContent = button.getAttribute('data-platform');
        document.getElementById('deleteQrForm').action = '/qr-codes/' + id;
    });
});
</script>