@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Pairs Bonus Limit Settings')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pairs.management.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label fw-bold">@lang('Pairs Bonus Limit')</label>
                            <p class="text-muted">@lang('Set the maximum number of pairs a user can be paid for.')</p>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input class="form-control" type="number" name="pairs_bonus_limit" value="{{ $settings->pairs_bonus_limit ?? 500 }}" required>
                                <span class="input-group-text">@lang('Pairs')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="form-control-label fw-bold">@lang('Update Security Password')</label>
                            <p class="text-muted">@lang('Leave blank if you don\'t want to change the password.')</p>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" type="password" name="password" placeholder="@lang('New Password')">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save Settings')</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">@lang('Limit Status')</h5>
            </div>
            <div class="card-body">
                 <div class="form-group row">
                    <div class="col-md-4">
                        <label class="form-control-label fw-bold">@lang('Enable/Disable Pair Limit')</label>
                        <p class="text-muted">@lang('If enabled, the pairs bonus will be capped at the limit set above.')</p>
                    </div>
                    <div class="col-md-8">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="limitStatusSwitch" @if($settings->limit_status) checked @endif>
                            <label class="form-check-label" for="limitStatusSwitch" id="limitStatusLabel">
                                @if($settings->limit_status) @lang('Limit is Active') @else @lang('Limit is Inactive') @endif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Password Confirmation Modal --}}
<div class="modal fade" id="passwordConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">@lang('Security Confirmation')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="passwordConfirmForm">
                <div class="modal-body">
                    <p>@lang('Please enter your security password to change the status.')</p>
                    <div class="form-group">
                        <label for="securityPassword" class="form-control-label fw-bold">@lang('Password')</label>
                        <input type="password" class="form-control" id="securityPassword" name="password" required>
                        <input type="hidden" id="newStatus" name="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
(function($) {
    "use strict";

    let originalSwitchState = $('#limitStatusSwitch').is(':checked');

    $('#limitStatusSwitch').on('click', function(e) {
        e.preventDefault();
        // The new status is the opposite of the original state before any changes.
        var newStatus = !originalSwitchState; 

        $('#newStatus').val(newStatus ? 1 : 0);
        $('#passwordConfirmationModal').modal('show');
    });

    $('#passwordConfirmForm').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let password = form.find('#securityPassword').val();
        let status = form.find('#newStatus').val(); // Read status from hidden input

        $.ajax({
            url: "{{ route('admin.pairs.management.verify.password') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                password: password
            },
            success: function(response) {
                if (response.success) {
                    // Password is correct, now update the status
                    updateStatus(status);
                    $('#passwordConfirmationModal').modal('hide');
                } else {
                    notify('error', 'Incorrect password.');
                }
            },
            error: function() {
                notify('error', 'An error occurred. Please try again.');
            }
        });
    });

    function updateStatus(status) {
        $.ajax({
            url: "{{ route('admin.pairs.management.status.update') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                if (response.success) {
                    let switchInput = $('#limitStatusSwitch');
                    let switchLabel = $('#limitStatusLabel');
                    let isChecked = status == 1;

                    switchInput.prop('checked', isChecked);
                    originalSwitchState = isChecked; // Update the original state

                    if (isChecked) {
                        switchLabel.text('@lang('Limit is Active')');
                    } else {
                        switchLabel.text('@lang('Limit is Inactive')');
                    }
                    notify('success', response.message);
                } else {
                    notify('error', response.message || 'Could not update status.');
                }
            },
            error: function() {
                notify('error', 'An error occurred while updating status.');
            }
        });
    }

    // Reset the switch if the modal is closed without confirmation
    $('#passwordConfirmationModal').on('hidden.bs.modal', function() {
        // No need to revert here as preventDefault stops the initial change.
        $('#passwordConfirmForm')[0].reset();
    });

})(jQuery);
</script>
@endpush
