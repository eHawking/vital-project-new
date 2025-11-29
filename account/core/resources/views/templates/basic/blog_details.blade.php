@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Include Modern Auth Theme CSS -->
    @include($activeTemplate . 'css.modern-auth')

    <section class="blog-details padding-top padding-bottom">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8">
                    <div class="premium-card p-0 overflow-hidden">
                        <div class="details-thumb position-relative">
                            <img src="{{ frontendImage('blog',@$blog->data_values->image, '820x540') }}" alt="blog" class="w-100">
                            <div class="position-absolute bottom-0 start-0 w-100 p-4 bg-gradient-to-t from-black to-transparent">
                                <h3 class="title text-white mb-0 display-6 fw-bold">{{ __($blog->data_values->title) }}</h3>
                            </div>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="blog-content text-white-50">
                                @php echo $blog->data_values->description @endphp
                            </div>
                            
                            <div class="mt-5 border-top border-secondary border-opacity-25 pt-4">
                                <div class="blog-details__share d-flex align-items-center flex-wrap gap-3">
                                    <span class="fw-bold text-white">@lang('Share Now :')</span>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" style="width: 36px; height: 36px;">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a class="btn btn-outline-info btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            href="https://x.com/intent/tweet?text={{ __($blog->data_values->title) }}&amp;url={{ urlencode(url()->current()) }}" target="_blank" style="width: 36px; height: 36px;">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ __($blog->data_values->title) }}&amp;summary=@php echo strLimit(strip_tags($blog->data_values->description),100) @endphp" target="_blank" style="width: 36px; height: 36px;">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                            href="https://www.instagram.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" style="width: 36px; height: 36px;">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="comments-area mt-5 premium-card p-4">
                        <div class="fb-comments" data-width="100%" data-href="{{ url()->current() }}" data-numposts="5"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <div class="premium-card p-4">
                            <h5 class="title text-white mb-4 border-bottom border-secondary border-opacity-25 pb-3">@lang('Recent Post')</h5>
                            <div class="recent-post-wrapper d-flex flex-column gap-4">
                                @foreach ($latestBlogs as $blog)
                                    <div class="recent-post-item d-flex gap-3 align-items-center hover-lift">
                                        <div class="thumb rounded overflow-hidden flex-shrink-0" style="width: 80px; height: 60px;">
                                            <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '820x540') }}"
                                                alt="blog" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <div class="content">
                                            <h6 class="title mb-1">
                                                <a href="{{ route('blog.details', $blog->slug) }}" class="text-white text-decoration-none text-hover-primary transition-all small fw-bold">{{ __($blog->data_values->title) }}</a>
                                            </h6>
                                            <span class="date text-white-50 small"><i class="las la-calendar-check me-1"></i>{{ showDateTime($blog->created_at, 'd M, Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @push('style')
    <style>
        .bg-gradient-to-t {
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
        }
        .text-hover-primary:hover {
            color: var(--accent-blue) !important;
        }
        .hover-lift:hover {
            transform: translateX(5px);
            transition: transform 0.3s ease;
        }
    </style>
    @endpush
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
