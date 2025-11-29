@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Include Modern Auth Theme CSS for public pages -->
    @include($activeTemplate . 'css.modern-auth')

    <!-- Blog Section Starts Here -->
    <section class="blog-section padding-bottom padding-top">
        <div class="container">
            <h2 class="text-center text-white mb-5 display-5 fw-bold">@lang('Latest News')</h2>
            
            <div class="row justify-content-center gy-4">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="premium-card h-100 d-flex flex-column overflow-hidden" style="background: var(--card-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 20px; transition: transform 0.3s ease;">
                            <div class="post-thumb position-relative">
                                <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image) }}" alt="blog" class="w-100 object-fit-cover" style="height: 220px;">
                                <div class="meta-date position-absolute top-0 end-0 bg-primary text-white p-2 text-center rounded-bottom-start fw-bold">
                                    <span class="d-block fs-5 lh-1">{{ showDateTime($blog->created_at, 'd') }}</span>
                                    <span class="small text-uppercase">{{ showDateTime($blog->created_at, 'M') }}</span>
                                </div>
                            </div>
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h4 class="title mb-3">
                                    <a href="{{ route('blog.details', $blog->slug) }}" class="text-white text-decoration-none text-hover-primary transition-all">{{ __($blog->data_values->title) }}</a>
                                </h4>
                                <div class="text-white-50 mb-4 flex-grow-1">
                                    @php echo shortDescription(strip_tags($blog->data_values->description),120) @endphp
                                </div>
                                <a class="account--btn text-center mt-auto text-decoration-none" href="{{ route('blog.details', $blog->slug) }}">@lang('Read More')</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($blogs->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>
    </section>
    <!-- Blog Section Ends Here -->

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
    
    @push('style')
    <style>
        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .text-hover-primary:hover {
            color: var(--accent-blue) !important;
        }
    </style>
    @endpush
@endsection
