@extends($activeTemplate . 'layouts.app')
@section('panel')




    @include($activeTemplate.'partials.dashboard')
<style>
    .container, .container-lg, .container-md, .container-sm, .container-xl {
        max-width: 100%;
    }
</style>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            window.addEventListener('scroll', function(){
              var header = document.querySelector('header');
              header.classList.toggle('sticky', window.scrollY > 0);
            });   
        })(jQuery);
    </script>
@endpush
