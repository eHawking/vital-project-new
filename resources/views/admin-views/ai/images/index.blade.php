@extends('layouts.admin.app')

@section('title', translate('AI Generated Images'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex gap-2">
            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/ai-icon.png') }}" alt="">
            {{ translate('AI Generated Images') }}
        </h2>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-2 mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1">{{ $stats['total'] }}</h3>
                        <div class="text-capitalize">{{ translate('total_images') }}</div>
                    </div>
                    <div>
                        <i class="fi fi-rr-picture text-primary" style="font-size: 40px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1">{{ $stats['product'] }}</h3>
                        <div class="text-capitalize">{{ translate('product_images') }}</div>
                    </div>
                    <div>
                        <i class="fi fi-rr-shopping-cart text-success" style="font-size: 40px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1">{{ $stats['placeholder'] }}</h3>
                        <div class="text-capitalize">{{ translate('placeholder_images') }}</div>
                    </div>
                    <div>
                        <i class="fi fi-rr-picture text-info" style="font-size: 40px;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-body">
                <div class="d-flex gap-2 justify-content-between align-items-center">
                    <div class="d-flex flex-column">
                        <h3 class="mb-1">{{ number_format($stats['total_size'] / 1024 / 1024, 2) }} MB</h3>
                        <div class="text-capitalize">{{ translate('total_storage') }}</div>
                    </div>
                    <div>
                        <i class="fi fi-rr-database text-warning" style="font-size: 40px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.ai-settings.images') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label">{{ translate('filter_by_type') }}</label>
                        <select name="type" class="form-control" onchange="this.form.submit()">
                            <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>{{ translate('all') }}</option>
                            <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>{{ translate('product_images') }}</option>
                            <option value="placeholder" {{ request('type') == 'placeholder' ? 'selected' : '' }}>{{ translate('placeholder_images') }}</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="form-label">{{ translate('search') }}</label>
                        <input type="text" name="search" class="form-control" placeholder="{{ translate('search_by_filename_or_prompt') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fi fi-rr-search"></i> {{ translate('search') }}
                            </button>
                            <a href="{{ route('admin.ai-settings.images') }}" class="btn btn-secondary">
                                <i class="fi fi-rr-cross-circle"></i> {{ translate('reset') }}
                            </a>
                            <button type="button" class="btn btn-danger" onclick="clearOldImages()">
                                <i class="fi fi-rr-trash"></i> {{ translate('clear_old') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Images Gallery -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="fi fi-rr-picture"></i>
                </span>
                {{ translate('image_gallery') }}
            </h5>
            <div>
                <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()" id="bulk-delete-btn" style="display:none;">
                    <i class="fi fi-rr-trash"></i> {{ translate('delete_selected') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($images->count() > 0)
                <div class="row g-3" id="image-gallery">
                    @foreach($images as $image)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="card h-100 ai-image-card">
                                <div class="card-img-top-wrapper position-relative">
                                    <input type="checkbox" class="image-checkbox" value="{{ $image->id }}" onchange="toggleBulkDelete()">
                                    <img src="{{ $image->url }}" class="card-img-top" alt="{{ $image->filename }}" style="height: 150px; object-fit: cover; cursor: pointer;" onclick="viewImage('{{ $image->url }}', '{{ $image->filename }}')">
                                    <span class="badge badge-{{ $image->type == 'product' ? 'success' : 'info' }} position-absolute" style="top: 5px; left: 5px;">
                                        {{ $image->type }}
                                    </span>
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title text-truncate mb-1" title="{{ $image->filename }}">{{ $image->filename }}</h6>
                                    <small class="text-muted d-block">{{ $image->angle }}</small>
                                    <small class="text-muted d-block">{{ number_format($image->size / 1024, 2) }} KB</small>
                                    <small class="text-muted d-block">{{ $image->created_at->format('Y-m-d H:i') }}</small>
                                    @if($image->product)
                                        <small class="text-primary d-block">{{ $image->product->name }}</small>
                                    @endif
                                </div>
                                <div class="card-footer p-2">
                                    <div class="d-flex gap-2 justify-content-between">
                                        <a href="{{ $image->url }}" download class="btn btn-sm btn-outline-primary">
                                            <i class="fi fi-rr-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteImage({{ $image->id }})">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $images->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="" style="width: 150px;">
                    <p class="mt-3">{{ translate('no_images_found') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageViewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<style>
.ai-image-card {
    transition: transform 0.2s;
}
.ai-image-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.card-img-top-wrapper {
    position: relative;
}
.image-checkbox {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    cursor: pointer;
    z-index: 10;
}
</style>

<script>
function viewImage(url, filename) {
    document.getElementById('modalImage').src = url;
    document.getElementById('imageViewModalLabel').textContent = filename;
    new bootstrap.Modal(document.getElementById('imageViewModal')).show();
}

function deleteImage(id) {
    Swal.fire({
        title: '{{ translate("are_you_sure") }}?',
        text: '{{ translate("you_wont_be_able_to_revert_this") }}!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ translate("yes_delete_it") }}!',
        cancelButtonText: '{{ translate("cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.ai-settings.images.destroy', '') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire('{{ translate("deleted") }}!', data.message, 'success');
                    location.reload();
                }
            });
        }
    });
}

function toggleBulkDelete() {
    const checked = document.querySelectorAll('.image-checkbox:checked').length;
    document.getElementById('bulk-delete-btn').style.display = checked > 0 ? 'block' : 'none';
}

function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.image-checkbox:checked')).map(cb => cb.value);
    
    if(ids.length === 0) return;
    
    Swal.fire({
        title: '{{ translate("are_you_sure") }}?',
        text: `{{ translate("delete") }} ${ids.length} {{ translate("images") }}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '{{ translate("yes_delete_them") }}!',
        cancelButtonText: '{{ translate("cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route('admin.ai-settings.images.bulk-delete') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ ids })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire('{{ translate("deleted") }}!', data.message, 'success');
                    location.reload();
                }
            });
        }
    });
}

function clearOldImages() {
    Swal.fire({
        title: '{{ translate("clear_old_images") }}',
        input: 'number',
        inputLabel: '{{ translate("delete_images_older_than_days") }}',
        inputValue: 30,
        showCancelButton: true,
        confirmButtonText: '{{ translate("clear") }}',
        cancelButtonText: '{{ translate("cancel") }}'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            fetch('{{ route('admin.ai-settings.images.clear-old') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ days: result.value })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire('{{ translate("cleared") }}!', `${data.count} ${data.message}`, 'success');
                    location.reload();
                }
            });
        }
    });
}
</script>
@endsection
