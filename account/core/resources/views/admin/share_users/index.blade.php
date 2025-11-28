@extends('admin.layouts.app')

@section('panel')
@push('style')
<style>
	  .custom-search-input {
        background-color: #ffffff; 
    }

    
    .custom-search-group {
        background-color: #ffffff; 
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0;
        right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #28a745;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .form-switch .form-check-input:checked {
        background-color: #28a745;
    }

    .table-responsive--md {
        overflow-x: auto;
    }
</style>
@endpush



<!-- User Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <button type="button" class="btn btn--primary mb-3" data-bs-toggle="modal" data-bs-target="#addShareUserModal">
                    Add Shared User
                </button>

                <div class="table-responsive--md table-responsive">
                    <table class="table table--light">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sharedUsers as $user)
                                <tr>
                                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>
                                        <form action="{{ route('admin.share.user.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="is_share" value="0">
                                            <label class="switch">
                                                <input type="checkbox" class="share-toggle" data-user-id="{{ $user->id }}" checked>
                                                <span class="slider"></span>
                                            </label>
                                            <button type="submit" class="d-none">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No shared users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $sharedUsers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Shared User Modal -->
<div class="modal fade" id="addShareUserModal" tabindex="-1" aria-labelledby="addShareUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.share.user.update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter username">
                        <div id="userResults" class="mt-3"></div>
                    </div>

                    <input type="hidden" name="user_id" id="userIdField">
                    <div id="userInfo" class="d-none mt-3">
                        <div class="mb-2">
                            <strong>Full Name:</strong> <span id="fullName"></span>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="shareSwitch" name="is_share" value="1" checked>
                            <label class="form-check-label" for="shareSwitch">Enable Sharing</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn--primary" id="saveShareStatus" disabled>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDisableShareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to disable sharing for this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn--danger" id="confirmDisableShareBtn">Disable Sharing</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('breadcrumb-plugins')
    <form action="{{ route('admin.share.users') }}" method="GET" class="form-inline">
        <div class="input-group custom-search-group">
            <input type="text" name="search" class="form-control custom-search-input" placeholder="Search by full name, username..." value="{{ request('search') }}">
            <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.share.users') }}" class="btn btn--danger input-group-text"><i class="fa fa-times"></i></a>
            @endif
        </div>
    </form>
@endpush
@push('script')
<script>
$(document).ready(function () {
    let selectedUserId = null;
    let currentForm = null;

    // Modal search
    $('#username').on('input', function () {
        let query = $(this).val();
        if (query.length < 2) return;

        $.get("{{ route('admin.share.user.search') }}", { username: query }, function (response) {
            $('#userResults').empty();
            if (!response.found) {
                $('#userResults').append('<p class="text-danger">User not found</p>');
                $('#userInfo').addClass('d-none');
                $('#saveShareStatus').prop('disabled', true);
                selectedUserId = null;
                return;
            }

            $('#userResults').append(`
                <div class="alert alert-success mb-0">
                    <strong>${response.name}</strong><br>
                    <small>${response.username}</small>
                </div>
            `);
            selectedUserId = response.id;
            $('#fullName').text(response.name);
            $('#shareSwitch').prop('checked', response.is_shared);
            $('#userIdField').val(response.id);
            $('#userInfo').removeClass('d-none');
            $('#saveShareStatus').prop('disabled', false);
        });
    });

    // Inline toggle handler
    $('.share-toggle').on('change', function () {
        const form = $(this).closest('form');
        const isChecked = $(this).is(':checked');

        if (!isChecked) {
            currentForm = form;
            $('#confirmDisableShareModal').modal('show');
            $(this).prop('checked', true); // Revert switch
        } else {
            form.submit();
        }
    });

    // Confirm disable sharing
    $('#confirmDisableShareBtn').on('click', function () {
        if (currentForm) {
            currentForm.find('input[name="is_share"]').val(0);
            currentForm.submit();
        }
    });

    // Reset form state on modal close
    $('#addShareUserModal').on('hidden.bs.modal', function () {
        $('#userIdField').val('');
        $('#fullName').text('');
        $('#shareSwitch').prop('checked', true);
        $('#userInfo').addClass('d-none');
        $('#saveShareStatus').prop('disabled', true);
        $('#username').val('');
    });

    // Reset form state on modal close
    $('#confirmDisableShareModal').on('hidden.bs.modal', function () {
        if (currentForm) {
            currentForm.find('input[name="is_share"]').val(1);
            currentForm.find('input[type="checkbox"]').prop('checked', true);
        }
        currentForm = null;
    });
});
</script>
@endpush