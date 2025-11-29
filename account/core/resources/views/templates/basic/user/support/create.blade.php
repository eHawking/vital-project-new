@extends($activeTemplate . 'layouts.master')
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <h4 class="text-white m-0"><i class="las la-plus-circle"></i> @lang('Open New Ticket')</h4>
                <a href="{{ route('ticket.index') }}" class="btn btn-outline-light border-secondary">
                    <i class="las la-list"></i> @lang('My Support Ticket')
                </a>
            </div>

            <div class="premium-card">
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" class="disableSubmission" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="form-group col-md-6">
                                <label class="form-label text-white-50">@lang('Subject')</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control bg-transparent text-white border-secondary" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label text-white-50">@lang('Priority')</label>
                                <select name="priority" class="form-select bg-transparent text-white border-secondary select2" data-minimum-results-for-search="-1" required>
                                    <option value="3" class="text-dark">@lang('High')</option>
                                    <option value="2" class="text-dark">@lang('Medium')</option>
                                    <option value="1" class="text-dark">@lang('Low')</option>
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label class="form-label text-white-50">@lang('Message')</label>
                                <textarea name="message" id="inputMessage" rows="6" class="form-control bg-transparent text-white border-secondary" required>{{ old('message') }}</textarea>
                            </div>

                            <div class="col-md-9">
                                <button type="button" class="btn btn-sm btn-outline-light border-secondary addAttachment my-2"> <i class="fas fa-plus"></i> @lang('Add Attachment')
                                </button>
                                <p class="mb-2"><span class="text-info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                <div class="row fileUploadsContainer g-3">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100 my-2 pulse-animation" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;"><i class="las la-paper-plane"></i> @lang('Submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <!-- Include Icon Enhancer -->
    @include($activeTemplate . 'js.icon-enhancer')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control bg-transparent text-white border-secondary" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg-danger border-danger text-white"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
