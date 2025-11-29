@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <!-- Include Modern Finance Theme CSS -->
    @include($activeTemplate . 'css.modern-finance-theme')
    @include($activeTemplate . 'css.mobile-fixes')

    <div class="{{ auth()->check() ? '' : 'padding-top padding-bottom' }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="premium-card mb-4">
                        <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <h5 class="mt-0 text-white">
                                @php echo $myTicket->statusBadge; @endphp
                                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                            </h5>
                            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                <button class="btn btn-danger close-button btn-sm confirmationBtn pulse-animation" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" type="button"><i class="fas fa-times-circle"></i> @lang('Close Ticket')
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <form class="disableSubmission" method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-between g-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea class="form-control bg-transparent text-white border-secondary" name="message" rows="4" required placeholder="@lang('Write your reply here...')">{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <button class="btn btn-sm btn-outline-light border-secondary addAttachment my-2" type="button"> <i class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                        <p class="mb-2"><span class="text-info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row fileUploadsContainer g-3">
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary w-100 my-2 pulse-animation" type="submit" style="background: var(--grad-primary); border: none; padding: 12px; font-weight: 600;"><i class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="premium-card">
                        <div class="card-body">
                            @forelse($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="support-message py-3 mb-3 rounded" style="background: rgba(255, 255, 255, 0.05); border-left: 4px solid var(--bs-primary);">
                                        <div class="row g-0">
                                            <div class="col-md-3 border-end border-secondary border-opacity-25 text-end pe-3">
                                                <h5 class="my-2 text-white">{{ $message->ticket->name }}</h5>
                                                <small class="text-white-50">@lang('User')</small>
                                            </div>
                                            <div class="col-md-9 ps-3">
                                                <p class="text-white-50 fw-bold mb-2 small">
                                                    @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                                <p class="text-white">{{ $message->message }}</p>
                                                @if ($message->attachments->count() > 0)
                                                    <div class="mt-3">
                                                        @foreach ($message->attachments as $k => $image)
                                                            <a class="me-3 text-info" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="support-message py-3 mb-3 rounded reply-bg" style="background: rgba(255, 193, 7, 0.1); border-left: 4px solid #ffc107;">
                                        <div class="row g-0">
                                            <div class="col-md-3 border-end border-warning border-opacity-25 text-end pe-3">
                                                <h5 class="my-2 text-white">{{ $message->admin->name }}</h5>
                                                <p class="lead text-warning mb-0 small">@lang('Staff')</p>
                                            </div>
                                            <div class="col-md-9 ps-3">
                                                <p class="text-warning fw-bold mb-2 small">
                                                    @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ h:i a') }}</p>
                                                <p class="text-white">{{ $message->message }}</p>
                                                @if ($message->attachments->count() > 0)
                                                    <div class="mt-3">
                                                        @foreach ($message->attachments as $k => $image)
                                                            <a class="me-3 text-warning" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa-regular fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="empty-message text-center p-5">
                                    <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty" style="width: 100px; opacity: 0.5;">
                                    <h5 class="text-white-50 mt-3">@lang('No replies found here!')</h5>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <x-confirmation-modal base="true"/>
@endpush


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
